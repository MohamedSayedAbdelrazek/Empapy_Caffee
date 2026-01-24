{{-- Permissions Section Component --}}
@php
    $groupedPermissions = \App\Models\Permission::grouped();
    $groupLabels = \App\Models\Permission::groupLabels();
    $userPermissions = isset($staff) ? $staff->permissions->pluck('id')->toArray() : [];
@endphp

<div class="col-12" id="permissionsSection">
    <div class="card bg-light">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-shield-lock me-2"></i>الصلاحيات
            </h5>
            <p class="text-muted small mb-3">
                اختر الصلاحيات التي تريد منحها لهذا الموظف. يمكنك منح صلاحيات مخصصة لأي دور.
            </p>

            <div class="row g-3">
                @foreach ($groupedPermissions as $group => $permissions)
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded p-3 bg-white h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">{{ $groupLabels[$group] ?? $group }}</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary select-all-btn"
                                    data-group="{{ $group }}">
                                    <i class="bi bi-check-all"></i> الكل
                                </button>
                            </div>
                            <hr class="my-2">
                            @foreach ($permissions as $permission)
                                <div class="form-check mb-2">
                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                        name="permissions[]" value="{{ $permission->id }}"
                                        id="perm_{{ $permission->id }}" data-group="{{ $group }}"
                                        {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="perm_{{ $permission->id }}">
                                        {{ $permission->display_name_ar }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Select all buttons
            document.querySelectorAll('.select-all-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const group = this.dataset.group;
                    const checkboxes = document.querySelectorAll(
                        `.permission-checkbox[data-group="${group}"]`);
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

                    checkboxes.forEach(cb => {
                        cb.checked = !allChecked;
                    });

                    // Update button text
                    this.innerHTML = allChecked ?
                        '<i class="bi bi-check-all"></i> الكل' :
                        '<i class="bi bi-x-circle"></i> إلغاء';
                });
            });
        });
    </script>
@endpush
