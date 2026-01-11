@extends('admin.layouts.app')

@section('title', 'رسائل التواصل')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">📬 رسائل التواصل</h1>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-lg-3">
                <div class="admin-card text-center">
                    <div class="fs-1 mb-2">📧</div>
                    <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                    <small class="text-muted">إجمالي الرسائل</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-card text-center">
                    <div class="fs-1 mb-2">🆕</div>
                    <h3 class="fw-bold text-warning mb-1">{{ $stats['new'] }}</h3>
                    <small class="text-muted">رسائل جديدة</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-card text-center">
                    <div class="fs-1 mb-2">👁️</div>
                    <h3 class="fw-bold text-info mb-1">{{ $stats['read'] }}</h3>
                    <small class="text-muted">مقروءة</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-card text-center">
                    <div class="fs-1 mb-2">✅</div>
                    <h3 class="fw-bold text-success mb-1">{{ $stats['replied'] }}</h3>
                    <small class="text-muted">تم الرد</small>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="admin-card mb-4">
            <form action="" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">البحث</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="اسم، بريد، أو موضوع..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">الكل</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>جديد</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>مقروء</option>
                        <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>تم الرد</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-filter me-1"></i> تصفية
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Messages Table -->
        <div class="admin-card">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>المرسل</th>
                            <th>الموضوع</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th class="text-end">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr class="{{ $message->isNew() ? 'table-warning' : '' }}">
                                <td>
                                    <div>
                                        <strong>{{ $message->name }}</strong>
                                        @if ($message->user)
                                            <span class="badge bg-info ms-1">عضو</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $message->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span title="{{ $message->subject }}">{{ Str::limit($message->subject, 40) }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $message->status_color }}">{{ $message->status_label }}</span>
                                </td>
                                <td>
                                    @if ($message->created_at)
                                        <small class="text-muted">{{ $message->created_at->format('Y/m/d H:i') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.contacts.show', $message) }}"
                                        class="btn btn-sm btn-outline-light">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $message) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    لا توجد رسائل
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($messages->hasPages())
                <div class="mt-4">
                    {{ $messages->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
