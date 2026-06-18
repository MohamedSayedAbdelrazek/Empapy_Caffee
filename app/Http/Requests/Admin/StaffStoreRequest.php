<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StaffStoreRequest extends FormRequest
{
    /**
     * The staff write routes are admin-gated; the controller also sets the role
     * via direct assignment. Authorization is left to the route middleware so
     * the server-side role rule below still runs (and rejects a forged
     * role=admin) even if the route gate were ever loosened (SEC-02).
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Only an admin may create another admin; a non-admin can only create a
        // cashier — a forged role=admin is rejected server-side here (SEC-02).
        $actorIsAdmin = $this->user()?->isAdmin() ?? false;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', $actorIsAdmin ? 'in:admin,cashier' : 'in:cashier'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'role.required' => 'يجب اختيار الدور',
            'role.in' => 'لا يمكن إنشاء حساب مدير من هذه الصفحة',
        ];
    }
}
