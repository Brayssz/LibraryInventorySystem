
<div >
    
    <div class="modal fade" id="borrow-book-modal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrowBookModalLabel">Borrow Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="submit">
                        @csrf
                        <input type="hidden" wire:model="book_id">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class=" input w-100" id="quantity" wire:model.lazy="quantity" style="max-width: 100%;">
                            @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" rows="4" wire:model.lazy="remarks" placeholder="Enter request remarks"></textarea>
                            @error('remarks')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn" wire:loading.attr="disabled">Submit</button>
                        </div>
                    </form>
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
    
                    @this.call('checkLogin').then(response => {
                        if (response) {
                            @this.call('resetFields').then(() => {
                                @this.set('book_id', bookId);
                                @this.set('quantity', 0);
                                $('#borrow-book-modal').modal('show');
                            });
                        } 
                    });
    
                    // @this.call('resetFields').then(() => {
                    //     @this.set('book_id', bookId);
                    //     @this.set('quantity', 0);
                    //     $('#borrow-book-modal').modal('show');
                    // });
                }
            </script>
        @endpush
</div>
