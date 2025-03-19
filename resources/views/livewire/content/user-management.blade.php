<div class="modal fade" id="add-user-modal" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            @if ($submit_func == 'add-user')
                                <h4>Add User</h4>
                            @else
                                <h4>Edit User</h4>
                            @endif
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submit_user">
                            @csrf
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="new-employee-field">
                                        <div class="card-title-head" wire:ignore>
                                            <h6><span><i data-feather="info" class="feather-edit"></i></span>Personal
                                                Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="name">Name</label>
                                                    <input type="text" class="form-control" placeholder="Enter name"
                                                        id="name" wire:model.lazy="name">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Email</label>
                                                    <input type="email" class="form-control"
                                                        placeholder="e.g., name@mail.com" id="email"
                                                        wire:model.lazy="email">
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone_number">Phone Number</label>
                                                    <input type="text" id="phone_number" class="form-control not_pass"
                                                        placeholder="e.g., +63 999 999 9999"
                                                        wire:model.lazy="phone_number">
                                                    @error('phone_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            @if ($submit_func == 'edit-user')
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="status">Status</label>
                                                        <div wire:ignore>
                                                            <select class="select" id="status" name="status"
                                                                wire:model="status">
                                                                <option value="">Choose</option>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Deactivated</option>
                                                            </select>
                                                        </div>
                                                        @error('status')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="pass-info">
                                            <div class="card-title-head" wire:ignore>
                                                <h6><span><i data-feather="info"
                                                            class="feather-edit"></i></span>Password</h6>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 input-blocks">
                                                    <label for="password">Password</label>
                                                    <div class="mb-3 pass-group">
                                                        <input type="password" class="pass-input" id="password"
                                                            wire:model.lazy="password"
                                                            placeholder="Enter your password">
                                                        <span class="fas toggle-password fa-eye-slash"></span>
                                                    </div>
                                                    @error('password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 col-md-6 input-blocks">
                                                    <label for="password_confirmation">Confirm Password</label>
                                                    <div class="mb-3 pass-group">
                                                        <input type="password" class="pass-inputa"
                                                            id="password_confirmation"
                                                            wire:model.lazy="password_confirmation"
                                                            placeholder="Confirm your password">
                                                        <span class="fas toggle-passworda fa-eye-slash"></span>
                                                    </div>
                                                    @error('password_confirmation')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer-btn mb-4 mt-0">
                                <button type="button" class="btn btn-cancel me-2"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                handleUserActions();
            });

            function initSelect() {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }
            function handleUserActions() {
                $(document).on('change', '[id]', handleInputChange);
                $(document).on('click', '.add-user', openAddUserModal);
                $(document).on('click', '.edit-user', openEditUserModal);
            }

            function handleInputChange(e) {
                if ($(e.target).is('select') || $(e.target).is('.not_pass')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);

                    console.log(`${property}: ${value}`);
                }
            }

            function openAddUserModal() {
                @this.set('submit_func', 'add-user');

                @this.call('resetFields').then(() => {
                    initSelectVal("");
                    $('#add-user-modal').modal('show');
                });
            }

            function openEditUserModal() {
                const userId = $(this).data('userid');

                @this.set('submit_func', 'edit-user');
                @this.call('getUser', userId).then(() => {
                    populateEditForm();
                    $('#add-user-modal').modal('show');
                });
            }

            function initSelectVal(status) {
                $('#status').val(status).change();
            }

            function populateEditForm() {
                const status = @this.get('status');

                initSelect();
                initSelectVal(status);
            }
        </script>
    @endpush
</div>