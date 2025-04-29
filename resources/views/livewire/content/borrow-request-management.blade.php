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
                                            <h6><span><i data-feather="info" class="feather-edit"></i></span>Borrow Book
                                                Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="school_id">School</label>
                                                    <div wire:ignore>
                                                        <select id="school_id" class="form-control select"
                                                            wire:model="school_id">
                                                            <option value="">Select School</option>
                                                            @foreach ($schools as $school)
                                                                <option value="{{ $school->school_id }}">
                                                                    {{ $school->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('transmitted_office_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="book_id">Book to Borrowed</label>
                                                    <div wire:ignore>
                                                        <select id="book_id" class="form-control search-select"
                                                            wire:model="book_id">
                                                            <option value="">Select Book to Borrow</option>
                                                            @foreach ($books as $book)
                                                                <option value="{{ $book->book_id }}">
                                                                    {{ $book->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('transmitted_office_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="quantity">Quantity</label>
                                                    <div class="product-quantity px-4" wire:ignore>
                                                        <span class="quantity-btn me-auto">+<i
                                                                data-feather="plus-circle"
                                                                class="plus-circle"></i></span>
                                                        <input type="number" class="quntity-input not_pass"
                                                            id="quantity" wire:model.lazy="quantity">
                                                        <span class="quantity-btn ms-auto"><i
                                                                data-feather="minus-circle"
                                                                class="feather-search"></i></span>
                                                    </div>
                                                    @error('quantity')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="remarks">Remarks</label>
                                                    <div>
                                                        <textarea rows="7" cols="5" class="form-control"
                                                            wire:model.lazy="remarks"
                                                            placeholder="Enter request remarks"></textarea>
                                                    </div>
                                                    @error('remarks')
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
            $(document).ready(function () {
                $('.search-select').select2({
                    dropdownParent: $('#borrow-book-modal')
                });

                $('.search-select').on('select2:open', function () {
                    document.querySelector('.select2-container--open .select2-search__field').placeholder = 'Search books...';
                    
                });


            });

            document.addEventListener('DOMContentLoaded', () => {

                handleBorrowBookActions();
            });

            function handleBorrowBookActions() {
                $(document).on('change', '[id]:not([type="date"]):not([type="time"])', handleInputChange);
                $(document).on('click', '.add-book-request', openBorrowBookModal);
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

                if (bookId === null) {
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