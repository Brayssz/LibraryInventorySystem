<div>
    @push('scripts')
        <!-- Include Moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script>
            $(document).ready(function() {
                getAvailableBooks();
            });

            $("#searchInput").on("keyup", function() {
                const orderBy = $("#orderFilter").val();
                const searchQuery = $(this).val();
                getAvailableBooks(1, searchQuery, orderBy);
            });

            $('#orderFilter').on('change', function() {
                const searchQuery = $("#searchInput").val();
                const orderBy = $(this).val();
                console.log(orderBy);
                getAvailableBooks(1, searchQuery, orderBy);
            });

            const getAvailableBooks = function(page = 1, searchQuery = '', orderBy = '') {
                @this.call('getAvailableBooks', page, searchQuery, orderBy).then(response => {
                    console.log(response); // Handle the response here

                    const $container = $('#available-books-container');
                    $container.empty();

                    if (!response || !response.original || !response.original.data || response.original.data.length === 0) {
                        $container.append(`
                            <div class="d-flex flex-column align-items-center justify-content-center" style="width: 100%;">
                                <img src="img/no-data.jpg" alt="No data" style="max-width: 400px; margin-bottom: 15px;">
                                <p>No available books found.</p>
                            </div>
                        `);
                        return;
                    }

                    response.original.data.forEach(book => {
                        var photo = book.book_photo_path ? "storage/" + book.book_photo_path :
                            "img/no-book-image.jpg";
                        var publishedYear = new Date(book.published_date).getFullYear();
                        const bookCard = `
                            <div class="col mb-4">
                                <article class="card card--1" style="min-height: 475px; width: 100%;">
                                    <div class="card__info-hover">
                                        <div class="card__clock-info">
                                            <svg class="card__clock" viewBox="0 0 24 24">
                                                <path
                                                d="M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
                                            </svg>
                                            <span class="card__time">${moment(book.created_at).fromNow()}</span>
                                        </div>
                                    </div>
                                    <div class="card__img" style="background-image: url(${photo});"></div>
                                    <a href="#" class="card_link">
                                        <div class="card__img--hover" style="background-image: url(${photo});"></div>
                                    </a>
                                    <div class="card__info pb-2 pt-2">
                                        
                                        <h6 class="card__title fw-bold">${book.title}</h6>
                                        <div class="card__by">by <a href="#" class="card__author" title="author">${book.author}</a></div>
                                        <span class="card__category mt-2" style="display: inline-block; font-size: 11px;">Copyright &copy; ${publishedYear}</span>
                                        <span class="card__category w-100" style="display: inline-block; font-size: 11px; margin-top: -11px;">
                                            ${book.total_quantity} Available Copies
                                        </span>
                                    </div>
                                    <div class="m-3 d-flex justify-content-center mt-auto">
                                        <a class="btn btn-primary mt-1 borrow-book" style="background-color: var(--accent-color); border: none; font-size: 14px;" data-bookid="${book.book_id}">Borrow</a>
                                    </div>
                                </article>
                            </div>
                        `;

                        $container.append(bookCard);
                    });

                    const $paginationContainer = $('#paginationContainer');
                    $paginationContainer.empty();

                    let paginationHTML = `
                        <div class="pagination p1">
                            <ul>
                    `;

                    if (response.original.prev_page_url) {
                        paginationHTML += `
                            <a href="javascript:void(0);" onclick="getAvailableBooks(${response.original.current_page - 1}, '${searchQuery}')">
                                <li><</li>
                            </a>
                        `;
                    }

                    for (let i = 1; i <= response.original.last_page; i++) {
                        if (i === response.original.current_page) {
                            paginationHTML += `
                                <a class="is-active" href="javascript:void(0);">
                                    <li>${i}</li>
                                </a>
                            `;
                        } else {
                            paginationHTML += `
                                <a href="javascript:void(0);" onclick="getAvailableBooks(${i}, '${searchQuery}')">
                                    <li>${i}</li>
                                </a>
                            `;
                        }
                    }

                    if (response.original.next_page_url) {
                        paginationHTML += `
                            <a href="javascript:void(0);" onclick="getAvailableBooks(${response.original.current_page + 1}, '${searchQuery}')">
                                <li>></li>
                            </a>
                        `;
                    }

                    paginationHTML += `
                            </ul>
                        </div>
                    `;

                    $paginationContainer.append(paginationHTML);
                }).catch(error => {
                    console.error(error); // Handle any errors here
                });
            }

            $("borrow-book").on("click", function() {
                const bookId = $(this).data("bookid");
                console.log("Book ID:", bookId);
                @this.set('book_id', bookId);
            });
        </script>
    @endpush
</div>
