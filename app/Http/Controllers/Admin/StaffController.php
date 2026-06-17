<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members
     */
    public function index()
    {
        $staff = User::whereIn('role', ['admin', 'cashier'])
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created staff member
     */
    public function store(Request $request)
    {
        // Only an admin may create another admin (and assign permissions freely).
        // The staff write routes are admin-gated, but we re-check the actor here
        // so a forged role=admin POST is rejected server-side even if that route
        // gate is ever loosened.
        $actorIsAdmin = auth()->user()->isAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', $actorIsAdmin ? 'in:admin,cashier' : 'in:cashier'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'role.required' => 'يجب اختيار الدور',
            'role.in' => 'لا يمكن إنشاء حساب مدير من هذه الصفحة',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Admins bypass permission checks, so only cashiers receive explicit
        // permissions — and only those the current actor is allowed to grant.
        if ($validated['role'] === 'cashier') {
            $user->syncPermissions($this->grantablePermissions($validated['permissions'] ?? []));
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'تم إضافة الموظف بنجاح! ✨');
    }

    /**
     * Show the form for editing a staff member
     */
    public function edit(User $staff)
    {
        // Prevent editing customers
        if ($staff->isCustomer()) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'لا يمكن تعديل هذا الحساب');
        }

        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member
     */
    public function update(Request $request, User $staff)
    {
        // Prevent editing customers
        if ($staff->isCustomer()) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'لا يمكن تعديل هذا الحساب');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $staff->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => ['required', 'in:admin,cashier'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'role.required' => 'يجب اختيار الدور',
        ]);

        $isSelf = $staff->id === auth()->id();

        // Determine the effective role. A user can never change their own role,
        // and only an admin may assign the admin role to another account.
        if ($isSelf) {
            $role = $staff->role; // ignore any submitted role change for self
        } else {
            $role = $validated['role'];

            // Only an admin may grant/keep the admin role. A forged role=admin
            // from a non-admin is rejected here, not merely hidden in the UI.
            if ($role === 'admin' && !auth()->user()->isAdmin()) {
                return back()->withInput()
                    ->with('error', 'لا يمكن منح صلاحية المدير من هذه الصفحة');
            }

            // Prevent demoting the last remaining admin (would lock everyone out).
            if ($staff->isAdmin() && $role !== 'admin' && User::where('role', 'admin')->count() <= 1) {
                return back()->withInput()
                    ->with('error', 'لا يمكن تغيير دور آخر مدير في النظام');
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $role,
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $staff->update($data);

        // Never allow a user to edit their own permissions. Otherwise sync the
        // cashier's permissions (admins bypass permission checks, so clear theirs).
        if (!$isSelf) {
            if ($role === 'cashier') {
                $staff->syncPermissions($this->grantablePermissions($validated['permissions'] ?? []));
            } else {
                $staff->permissions()->detach();
            }
        }

        return redirect()->route('admin.staff.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح! ✨');
    }

    /**
     * Restrict the permissions being assigned to those the current actor may
     * grant. Admins may grant any permission; a non-admin may only grant
     * permissions they themselves hold. (Defense-in-depth: the staff write
     * routes are already admin-only.)
     *
     * @param  array<int|string>  $permissionIds
     * @return array<int|string>
     */
    private function grantablePermissions(array $permissionIds): array
    {
        $actor = auth()->user();

        if ($actor && $actor->isAdmin()) {
            return $permissionIds;
        }

        $ownPermissionIds = $actor
            ? $actor->permissions->pluck('id')->map(fn ($id) => (string) $id)->all()
            : [];

        return array_values(array_filter(
            $permissionIds,
            fn ($id) => in_array((string) $id, $ownPermissionIds, true)
        ));
    }

    /**
     * Remove the specified staff member
     */
    public function destroy(User $staff)
    {
        // Prevent self-deletion
        if ($staff->id === auth()->id()) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'لا يمكنك حذف حسابك الخاص!');
        }

        // Prevent deleting last admin
        if ($staff->isAdmin()) {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.staff.index')
                    ->with('error', 'لا يمكن حذف آخر مدير في النظام!');
            }
        }

        // Prevent deleting customers through this controller
        if ($staff->isCustomer()) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'لا يمكن حذف هذا الحساب من هنا');
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'تم حذف الموظف بنجاح!');
    }
}
