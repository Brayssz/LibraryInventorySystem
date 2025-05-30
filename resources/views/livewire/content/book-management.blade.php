<div class="modal fade" id="add-book-modal" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            @if ($submit_func == 'add-book')
                                <h4>Add Book</h4>
                            @else
                                <h4>Edit Book</h4>
                            @endif
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submit_book">
                            @csrf
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="new-book-field new-employee-field">
                                        <div class="card-title-head" wire:ignore>
                                            <h6><span><i data-feather="info" class="feather-edit"></i></span>Book
                                                Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="profile-pic-upload row col-4"
                                                x-data="{ photoPreview: @entangle('photoPreview'), photoName: '' }">
                                                <div class="profile-pic"
                                                    style="height: 400px !important; width: 300px !important;">
                                                    <template x-if="photoPreview">
                                                        <span><img :src="photoPreview" alt=""></span>
                                                    </template>
                                                    <template x-if="!photoPreview">
                                                        <span><i class="plus-down-add fa fa-plus"></i> Book Photo</span>
                                                    </template>
                                                </div>
                                                <div class="input-blocks mt-3 ms-0 ps-0 w-100 pe-4">
                                                    <div class="image-upload w-100">
                                                        <input type="file" wire:model="photo" x-ref="photo" x-on:change="
                                                        photoName = $refs.photo.files[0].name;
                                                        const reader = new FileReader();
                                                        reader.onload = (e) => {
                                                            photoPreview = e.target.result;
                                                        };
                                                        reader.readAsDataURL($refs.photo.files[0]);
                                                    ">
                                                    <template x-if="photoPreview">
                                                        <div class="image-uploads w-100">
                                                            <h4>Change Book Image</h4>
                                                        </div>
                                                    </template>
                                                    <template x-if="!photoPreview">
                                                        <div class="image-uploads w-100">
                                                            <h4>Upload Book Image</h4>
                                                        </div>
                                                    </template>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-8">

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="title">Title</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter title" id="title"
                                                                wire:model.lazy="title">
                                                            @error('title')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="author">Author</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter author" id="author"
                                                                wire:model.lazy="author">
                                                            @error('author')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="published_date">Published
                                                                Year</label>
                                                            <input type="year" id="published_date"
                                                                class="form-control not_pass"
                                                                placeholder="Enter published year"
                                                                wire:model.lazy="published_date">
                                                            @error('published_date')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    @if ($submit_func == 'edit-book')
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="status">Status</label>
                                                                <div wire:ignore>
                                                                    <select class="select" id="status" name="status"
                                                                        wire:model="status">
                                                                        <option value="">Choose</option>
                                                                        <option value="available">Available</option>
                                                                        <option value="unavailable">Unavailable</option>
                                                                    </select>
                                                                </div>
                                                                @error('status')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
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
                handleBookActions();
            });

            function initSelect() {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }
            function handleBookActions() {
                $(document).on('change', '[id]', handleInputChange);
                $(document).on('click', '.add-book', openAddBookModal);
                $(document).on('click', '.edit-book', openEditBookModal);
            }

            function handleInputChange(e) {
                if ($(e.target).is('select') || $(e.target).is('.not_pass')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);

                    console.log(`${property}: ${value}`);
                }
            }

            function openAddBookModal() {
                @this.set('submit_func', 'add-book');

                @this.call('resetFields').then(() => {
                    initSelectVal("");
                    $('#add-book-modal').modal('show');
                });
            }

            function openEditBookModal() {
                const bookId = $(this).data('bookid');

                console.log(bookId);

                @this.set('submit_func', 'edit-book');
                @this.call('getBook', bookId).then(() => {
                    populateEditForm();
                    $('#add-book-modal').modal('show');
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