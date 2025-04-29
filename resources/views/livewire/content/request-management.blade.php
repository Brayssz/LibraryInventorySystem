<div class="modal fade" id="receive-copies-modal" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Update Request</h4>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updateRequest">
                            @csrf
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="new-book-field">
                                        <div class="card-title-head" wire:ignore>
                                            <h6><span><i data-feather="info" class="feather-edit"></i></span>Request
                                                Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="delivered_quantity">Delivered Quantity</label>

                                                    <div class="product-quantity px-4" wire:ignore>
                                                        <span class="quantity-btn me-auto">+<i
                                                                data-feather="plus-circle"
                                                                class="plus-circle"></i></span>
                                                        <input type="number" class="quntity-input not_pass"
                                                            id="delivered_quantity" wire:model.lazy="delivered_quantity">
                                                        <span class="quantity-btn ms-auto"><i
                                                                data-feather="minus-circle"
                                                                class="feather-search"></i></span>
                                                    </div>
                                                    @error('delivered_quantity')
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
                                <button type="submit" class="btn btn-submit" wire:loading.attr="disabled">Submit</button>
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
                handleRequestManagementActions();
            });

            function handleRequestManagementActions() {
                $(document).on('click', '.approve-request', openApproveRequestModal);
                $(document).on('click', '.reject-request', RejectRequest);
            }

           

            function openApproveRequestModal() {
                showLoader();
                const requestId = $(this).data('requestid');
                const status = $(this).data('status');

                if(status !== "pending") {
                    messageAlert('Invalid Action', 'Request already approved.');
                    return;
                }
                @this.call('resetFields').then(() => {
                    @this.call('approveRequest');
                    @this.set('request_id', requestId);

                    @this.set('delivered_quantity', 0);
                    $('#receive-copies-modal').modal('show');
                    hideLoader();
                });
            }

            function RejectRequest() {
                const requestId = $(this).data('requestid');
                const status = $(this).data('status');
                
                if(status !== "pending") {
                    messageAlert('Invalid Action', 'Request already approved.');
                    return;
                }

                @this.call('resetFields').then(() => {


                    
                    @this.set('request_id', requestId);

                    @this.call('rejectRequest');
                });
            }
        </script>
    @endpush
</div>
