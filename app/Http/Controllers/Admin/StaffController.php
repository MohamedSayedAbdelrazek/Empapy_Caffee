<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaffStoreRequest;
use App\Http\Requests\Admin\StaffUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
    public function store(StaffStoreRequest $request)
    {
        // Validation (incl. the SEC-02 server-side role rule) lives in StaffStoreRequest.
        $validated = $request->validated();

        $user = new User([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);
        // Role is not mass-assignable (SEC-07); set the validated value
        // explicitly before save() so the created user gets the right role.
        $user->role = $validated['role'];
        $user->save();

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
    public function update(StaffUpdateRequest $request, User $staff)
    {
        // Prevent editing customers
        if ($staff->isCustomer()) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'لا يمكن تعديل هذا الحساب');
        }

        // Validation lives in StaffUpdateRequest; the role/self/last-admin guards below stay here.
        $validated = $request->validated();

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
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        // Role is not mass-assignable (SEC-07); set it explicitly. save() inside
        // update() persists this dirty attribute alongside the fillable changes.
        $staff->role = $role;
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
