<div class="modal fade" id="borrow-book-modal" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-mdM custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Borrow Book</h4>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submit">
                            @csrf
                            <input type="hidden" wire:model="book_id">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="new-book-field">
                                        <div class="card-title-head" wire:ignore>
                                            <h6><span><i data-feather="info" class="feather-edit"></i></span>Borrow Book Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="quantity">Quantity</label>
                                                    <div class="product-quantity px-4" wire:ignore>
                                                        <span class="quantity-btn me-auto">+<i data-feather="plus-circle"
                                                                class="plus-circle"></i></span>
                                                        <input type="number" class="quntity-input not_pass"
                                                            id="quantity" wire:model.lazy="quantity">
                                                        <span class="quantity-btn ms-auto"><i data-feather="minus-circle"
                                                                class="feather-search"></i></span>
                                                    </div>
                                                    @error('quantity')
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
                handleBorrowBookActions();
            });

            function handleBorrowBookActions() {
                $(document).on('change', '[id]:not([type="date"]):not([type="time"])', handleInputChange);
                $(document).on('click', '.borrow-book', openBorrowBookModal);
            }

            function handleInputChange(e) {
                if ($(e.target).is('select') || $(e.target).is('.not_pass')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);

                    console.log(`${property}: ${value}`);
                }
            }

            function openBorrowBookModal() {
                const bookId = $(this).data('bookid');

                if(bookId === null) {
                    messageAlert('Invalid Action', 'No Available Copies.');
                    return;
                }

                @this.call('resetFields').then(() => {
                    @this.set('book_id', bookId);
                    @this.set('quantity', 0);
                    $('#borrow-book-modal').modal('show');
                });
            }
        </script>
    @endpush
</div>
