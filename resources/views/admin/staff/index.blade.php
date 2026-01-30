@extends('admin.layouts.app')

@section('title', 'إدارة الفريق')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">👥 إدارة الفريق</h2>
            <p class="text-muted mb-0">إدارة المديرين والموظفين</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>إضافة موظف جديد
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>الموظف</th>
                            <th>البريد الإلكتروني</th>
                            <th>الهاتف</th>
                            <th>الدور</th>
                            <th>تاريخ الإنشاء</th>
                            <th style="width: 120px">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $member)
                            <tr>
                                <td>{{ $member->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm">
                                            @if ($member->avatar)
                                                <img src="{{ Storage::url($member->avatar) }}" alt="{{ $member->name }}"
                                                    class="rounded-circle"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #c9a227 0%, #e8c547 100%); color: #1a1a2e; font-weight: 600;">
                                                    {{ mb_substr($member->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $member->name }}</div>
                                            @if ($member->id === auth()->id())
                                                <small class="text-primary">أنت</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->phone ?? '-' }}</td>
                                <td>
                                    @if ($member->isAdmin())
                                        <span class="badge bg-primary">
                                            <i class="bi bi-shield-check me-1"></i>مدير
                                        </span>
                                    @else
                                        <span class="badge bg-info">
                                            <i class="bi bi-person-badge me-1"></i>موظف
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $member->created_at->format('Y/m/d') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-outline-primary"
                                            title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if ($member->id !== auth()->id())
                                            <form action="{{ route('admin.staff.destroy', $member) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="حذف">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-people display-4 text-muted"></i>
                                    <p class="text-muted mt-2">لا يوجد موظفين حالياً</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($staff->hasPages())
            <div class="card-footer">
                {{ $staff->links() }}
            </div>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
                            <small>المديرين</small>
                        </div>
                        <i class="bi bi-shield-check display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ \App\Models\User::where('role', 'cashier')->count() }}</h3>
                            <small>الموظفين</small>
                        </div>
                        <i class="bi bi-person-badge display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ \App\Models\User::whereIn('role', ['admin', 'cashier'])->count() }}</h3>
                            <small>إجمالي الفريق</small>
                        </div>
                        <i class="bi bi-people display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
