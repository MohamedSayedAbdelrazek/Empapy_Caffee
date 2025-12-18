@extends('admin.layouts.app')

@section('title', 'العملاء')

@section('content')
    <div class="page-header-admin mb-4">
        <h1 class="page-title-admin">العملاء</h1>
        <p class="page-subtitle-admin">إدارة عملاء المتجر</p>
    </div>

    <!-- Search -->
    <div class="admin-card mb-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو البريد أو الهاتف..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-light w-100">
                    <i class="bi bi-search"></i> بحث
                </button>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>العميل</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>عدد الطلبات</th>
                        <th>تاريخ التسجيل</th>
                        <th style="width: 80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td dir="ltr" class="text-end">{{ $user->phone ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $user->orders_count }} طلب</span>
                            </td>
                            <td>{{ $user->created_at->format('Y/m/d') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-light">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-people display-4 d-block mb-3"></i>
                                لا يوجد عملاء
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
