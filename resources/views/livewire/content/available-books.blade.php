<div>
    
    @push('scripts')
        <script>
            $(document).ready(function() {
                getAvailableBooks();
            });

            $("#searchInput").on("keyup", function() {
                const searchQuery = $(this).val();
                getAvailableBooks(1, searchQuery);
            });

            const getAvailableBooks = function(page = 1, searchQuery = '') {
                @this.call('getAvailableBooks', page, searchQuery).then(response => {
                    console.log(response); // Handle the response here

                    const $container = $('#available-books-container');
                    $container.empty();

                    if (!response || !response.original || !response.original.data || response.original.data.length === 0) {
                        $container.append('<p class="text-center">No available books found.</p>');
                        return;
                    }

                    response.original.data.forEach(book => {
                        var photo = book.book_photo_path ? "storage/" + book.book_photo_path :
                            "img/book.png";
                        var publishedYear = new Date(book.published_date).getFullYear();
                        const bookCard = `
                            <div class="col-lg-3">
                                <article class="card card--1" style="min-height: 550px; width: 100%;">
                                    <div class="card__info-hover"></div>
                                    <div class="card__img" style="background-image: url(${photo});"></div>
                                    <a href="#" class="card_link">
                                        <div class="card__img--hover" style="background-image: url(${photo});"></div>
                                    </a>
                                    <div class="card__info pb-2">
                                        <span class="card__category">${publishedYear}</span>
                                        <h4 class="card__title">${book.title}</h4>
                                        <div class="card__by mt-4">by <a href="#" class="card__author" title="author">${book.author}</a></div>
                                        <span class="card__category w-100" style="display: inline-block; font-size: 14px; margin-top: 15px;">
                                            ${book.total_quantity} Available Copies
                                        </span>
                                    </div>
                                    <div class="m-3 d-flex justify-content-end mt-auto">
                                        <a class="btn btn-primary mt-2 borrow-book" style="background-color: var(--accent-color); border: none;" data-bookid="${book.book_id}">Borrow</a>
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
