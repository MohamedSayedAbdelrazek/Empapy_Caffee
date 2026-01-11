@extends('admin.layouts.app')

@section('title', 'أعضاء نظام الولاء')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                        <li class="breadcrumb-item active">الأعضاء</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">👥 أعضاء نظام الولاء</h1>
            </div>
        </div>

        <!-- Filters -->
        <div class="admin-card mb-4">
            <form action="" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">البحث</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="اسم أو بريد إلكتروني..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">المستوى</label>
                    <select name="tier" class="form-select form-select-sm">
                        <option value="">الكل</option>
                        @foreach ($tiers as $tier)
                            <option value="{{ $tier->slug }}" {{ request('tier') === $tier->slug ? 'selected' : '' }}>
                                {{ $tier->icon }} {{ $tier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">ترتيب حسب</label>
                    <select name="sort" class="form-select form-select-sm">
                        <option value="available_points"
                            {{ request('sort', 'available_points') === 'available_points' ? 'selected' : '' }}>النقاط
                            المتاحة</option>
                        <option value="total_earned" {{ request('sort') === 'total_earned' ? 'selected' : '' }}>إجمالي
                            المكتسب</option>
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>تاريخ الانضمام
                        </option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-filter me-1"></i> تصفية
                    </button>
                    <a href="{{ route('admin.loyalty.users') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-x"></i> مسح
                    </a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>العضو</th>
                            <th>المستوى</th>
                            <th>النقاط المتاحة</th>
                            <th>إجمالي المكتسب</th>
                            <th>إجمالي المستخدم</th>
                            <th>تاريخ الانضمام</th>
                            <th class="text-end">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $loyalty)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px; font-size: 0.9rem;">
                                            {{ mb_substr($loyalty->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $loyalty->user->name ?? 'مستخدم محذوف' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $loyalty->user->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($loyalty->tier)
                                        <span class="badge" style="background: {{ $loyalty->tier->color ?? '#6c757d' }};">
                                            {{ $loyalty->tier->icon }} {{ $loyalty->tier->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $loyalty->current_tier }}</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-warning">{{ number_format($loyalty->available_points) }}</td>
                                <td class="text-success">+{{ number_format($loyalty->total_earned) }}</td>
                                <td class="text-danger">-{{ number_format($loyalty->total_redeemed) }}</td>
                                <td><small class="text-muted">{{ $loyalty->created_at->format('Y/m/d') }}</small></td>
                                <td class="text-end">
                                    <a href="{{ route('admin.loyalty.users.show', $loyalty->user) }}"
                                        class="btn btn-sm btn-outline-light">
                                        <i class="bi bi-eye"></i> عرض
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    لا يوجد أعضاء في نظام الولاء بعد
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="mt-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
