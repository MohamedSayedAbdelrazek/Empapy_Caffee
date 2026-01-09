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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', 'in:admin,cashier'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'role.required' => 'يجب اختيار الدور',
            'role.in' => 'الدور غير صحيح',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

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
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'role.required' => 'يجب اختيار الدور',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح! ✨');
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
