@extends('admin.layouts.app')

@section('title', 'عرض الرسالة')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">رسائل التواصل</a></li>
                        <li class="breadcrumb-item active">عرض الرسالة</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">📧 {{ $message->subject }}</h1>
            </div>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-right me-1"></i> رجوع
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Message Content -->
            <div class="col-lg-8">
                <div class="admin-card">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h5 class="mb-1">{{ $message->name }}</h5>
                            <a href="mailto:{{ $message->email }}" class="text-warning">{{ $message->email }}</a>
                            @if ($message->user)
                                <span class="badge bg-info ms-2">عضو مسجل</span>
                            @endif
                        </div>
                        <span class="badge bg-{{ $message->status_color }} fs-6">{{ $message->status_label }}</span>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            @if ($message->created_at)
                                {{ $message->created_at->format('Y/m/d H:i') }}
                                ({{ $message->created_at->diffForHumans() }})
                            @else
                                غير محدد
                            @endif
                        </small>
                        @if ($message->read_at)
                            <br>
                            <small class="text-muted">
                                <i class="bi bi-eye me-1"></i> تمت القراءة: {{ $message->read_at->format('Y/m/d H:i') }}
                            </small>
                        @endif
                    </div>

                    <hr class="border-secondary">

                    <div class="message-content" style="white-space: pre-wrap; line-height: 1.8;">{{ $message->message }}
                    </div>
                </div>
            </div>

            <!-- Actions Sidebar -->
            <div class="col-lg-4">
                <!-- Update Status -->
                <div class="admin-card mb-4">
                    <h6 class="mb-3"><i class="bi bi-gear me-2"></i>تحديث الحالة</h6>
                    <form action="{{ route('admin.contacts.update', ['contact' => $message->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>🆕 جديد</option>
                                <option value="read" {{ $message->status === 'read' ? 'selected' : '' }}>👁️ مقروء
                                </option>
                                <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>✅ تم الرد
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات الإدارة</label>
                            <textarea name="admin_notes" class="form-control" rows="3" placeholder="أضف ملاحظاتك هنا...">{{ $message->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg me-1"></i> حفظ
                        </button>
                    </form>
                </div>

                <!-- Quick Actions -->
                <div class="admin-card">
                    <h6 class="mb-3"><i class="bi bi-lightning me-2"></i>إجراءات سريعة</h6>
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}"
                            class="btn btn-outline-light">
                            <i class="bi bi-reply me-2"></i>الرد بالإيميل
                        </a>
                        <form action="{{ route('admin.contacts.destroy', $message) }}" method="POST"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-2"></i>حذف الرسالة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
