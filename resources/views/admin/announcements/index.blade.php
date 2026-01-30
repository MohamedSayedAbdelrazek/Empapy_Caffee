@extends('admin.layouts.app')

@section('title', 'إدارة الإعلانات')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mt-4">الإعلانات</h1>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>إضافة إعلان جديد
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                @if ($announcements->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-megaphone display-1 text-muted"></i>
                        <p class="text-muted mt-3">لا توجد إعلانات حالياً</p>
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                            إضافة إعلان الآن
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="announcementsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <i class="bi bi-arrows-move" title="اسحب لإعادة الترتيب"></i>
                                    </th>
                                    <th width="80">الترتيب</th>
                                    <th>الرسالة</th>
                                    <th width="100">الحالة</th>
                                    <th width="200">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-announcements">
                                @foreach ($announcements as $announcement)
                                    <tr data-id="{{ $announcement->id }}">
                                        <td class="handle" style="cursor: move;">
                                            <i class="bi bi-grip-vertical"></i>
                                        </td>
                                        <td>{{ $announcement->order }}</td>
                                        <td>{{ $announcement->message_ar }}</td>
                                        <td>
                                            <button
                                                class="btn btn-sm btn-toggle-status {{ $announcement->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                onclick="toggleStatus({{ $announcement->id }}, this)">
                                                <i class="bi bi-{{ $announcement->is_active ? 'eye' : 'eye-slash' }}"></i>
                                                {{ $announcement->is_active ? 'نشط' : 'معطّل' }}
                                            </button>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> تعديل
                                            </a>
                                            <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا الإعلان?')">
                                                    <i class="bi bi-trash"></i> حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($announcements->isNotEmpty())
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
            <script>
                // Sortable for drag & drop reordering
                const tbody = document.getElementById('sortable-announcements');
                if (tbody) {
                    Sortable.create(tbody, {
                        handle: '.handle',
                        animation: 150,
                        onEnd: function(evt) {
                            const order = Array.from(tbody.querySelectorAll('tr')).map(tr => tr.dataset.id);

                            fetch('{{ route('admin.announcements.reorder') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        order: order
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update order numbers in table
                                        tbody.querySelectorAll('tr').forEach((tr, index) => {
                                            tr.querySelector('td:nth-child(2)').textContent = index + 1;
                                        });
                                    }
                                });
                        }
                    });
                }

                // Toggle active status
                function toggleStatus(id, button) {
                    fetch(`/admin/announcements/${id}/toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.is_active) {
                                    button.classList.remove('btn-secondary');
                                    button.classList.add('btn-success');
                                    button.innerHTML = '<i class="bi bi-eye"></i> نشط';
                                } else {
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-secondary');
                                    button.innerHTML = '<i class="bi bi-eye-slash"></i> معطّل';
                                }
                            }
                        });
                }
            </script>
        @endpush
    @endif
@endsection
