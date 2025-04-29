@extends('layouts.app-layout')

@section('title', 'Book Request Form')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Return Borrow Books</h4>
                    <h6>View and Return Borrow Books.</h6>
                </div>
            </div>

        </div>
        <!-- /book list -->
        <div class="card table-list-card">
            <div class="card-body pb-0">
                <div class="table-top table-top-two table-top-new d-flex ">
                    <div class="search-set mb-0 d-flex w-100 justify-content-start">

                        <div class="search-input text-left">
                            <a href="" class="btn btn-searchset"><i data-feather="search"
                                    class="feather-search"></i></a>
                        </div>

                        <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1">
                          
                           

                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <select class="select school_filter form-control">
                                        <option value="">School</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->school_id }}">{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group ">
                                    <select class="select status_filter form-control">
                                        <option value="">Status</option>
                                        <option value="borrowed">Borrowed</option>
                                        <option value="partially_returned">Partially Returned</option>
                                        <option value="returned">Returned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <select class="search-select book_filter form-control">
                                        <option value="">Book</option>
                                        @foreach ($books as $book)
                                            <option value="{{ $book->book_id }}">{{ $book->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table book-table pb-3">
                        <thead>
                            <tr>
                                <th>Reference Code</th>
                                <th>School</th>
                                <th>Book</th>
                                <th>Remaining Quantity</th>
                                <th>Returned Quantity</th>
                                <th>Lost Quantity</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @livewire('content.return-books')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.search-select').select2();

            $('.search-select').on('select2:open', function() {
                document.querySelector('.select2-container--open .select2-search__field').placeholder =
                    'Search books here...';

            });

            @if (session('message'))
                toastr.success("{{ session('message') }}", "Success", {
                    closeButton: true,
                    progressBar: true,
                });
            @endif

            if ($('.book-table').length > 0) {
                var table = $('.book-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    'pagingType': 'numbers',
                    "language": {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search...",
                        info: "_START_ - _END_ of _TOTAL_ items",
                    },
                    "ajax": {
                        "url": "/borrowed-books",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function(d) {
                            d.status = $('.status_filter').val();
                            d.book_id = $('.book_filter').val();
                            d.school_id = $('.school_filter').val();
                        },
                        "dataSrc": "data"
                    },
                    "columns": [
                        {
                            "data": "transaction.reference_code.reference_code"
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                const colors = {
                                    A: 'bg-primary',
                                    B: 'bg-success',
                                    C: 'bg-info',
                                    D: 'bg-warning',
                                    E: 'bg-danger',
                                    F: 'bg-secondary',
                                    G: 'bg-dark',
                                    H: 'bg-light',
                                    I: 'bg-primary',
                                    J: 'bg-success',
                                    K: 'bg-info',
                                    L: 'bg-warning',
                                    M: 'bg-danger',
                                    N: 'bg-secondary',
                                    O: 'bg-dark',
                                    P: 'bg-light',
                                    Q: 'bg-primary',
                                    R: 'bg-success',
                                    S: 'bg-info',
                                    T: 'bg-warning',
                                    U: 'bg-danger',
                                    V: 'bg-secondary',
                                    W: 'bg-dark',
                                    X: 'bg-light',
                                    Y: 'bg-primary',
                                    Z: 'bg-success'
                                };

                                const firstLetter = row.transaction.reference_code.book_requests[0]
                                    .school.name ? row.transaction.reference_code.book_requests[0]
                                    .school.name.charAt(0).toUpperCase() : 'A';
                                const bgColor = colors[firstLetter] || 'bg-secondary';

                                return `
                                    <div class="userimgname">
                                        <a href="javascript:void(0);" class="product-img">
                                            <span class="avatar ${bgColor} avatar-rounded">
                                                <span class="avatar-title">${firstLetter}</span>
                                            </span>
                                        </a>
                                        <div>
                                            <a href="javascript:void(0);">${row.transaction.reference_code.book_requests[0].school.name}</a>
                                        </div>
                                    </div>
                                `;

                            }
                        },
                        {
                            "data": null,
                            "render": function (data, type, row) {
                                if (row.book.book_photo_path) {
                                    const avatarSrc = `/storage/${row.book.book_photo_path}`;
                                    return `
                                        <div class="userimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <img src="${avatarSrc}" alt="book cover" loading="lazy">
                                            </a>
                                            <div>
                                                <a href="javascript:void(0);">${row.book.title}</a>
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    const colors = {
                                        A: 'bg-primary',
                                        B: 'bg-success',
                                        C: 'bg-info',
                                        D: 'bg-warning',
                                        E: 'bg-danger',
                                        F: 'bg-secondary',
                                        G: 'bg-dark',
                                        H: 'bg-light',
                                        I: 'bg-primary',
                                        J: 'bg-success',
                                        K: 'bg-info',
                                        L: 'bg-warning',
                                        M: 'bg-danger',
                                        N: 'bg-secondary',
                                        O: 'bg-dark',
                                        P: 'bg-light',
                                        Q: 'bg-primary',
                                        R: 'bg-success',
                                        S: 'bg-info',
                                        T: 'bg-warning',
                                        U: 'bg-danger',
                                        V: 'bg-secondary',
                                        W: 'bg-dark',
                                        X: 'bg-light',
                                        Y: 'bg-primary',
                                        Z: 'bg-success'
                                    };

                                    const firstLetter = row.book.title ? row.book.title.charAt(0).toUpperCase() : 'A';
                                    const bgColor = colors[firstLetter] || 'bg-secondary';

                                    return `
                                        <div class="userimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <span class="avatar ${bgColor} avatar-rounded">
                                                    <span class="avatar-title">${firstLetter}</span>
                                                </span>
                                            </a>
                                            <div>
                                                <a href="javascript:void(0);">${row.book.title}</a>
                                            </div>
                                        </div>
                                    `;
                                }
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                let totalReturnedQuantity = 0;
                                if (row.return_transactions && row.return_transactions.length > 0) {
                                    totalReturnedQuantity = row.return_transactions.reduce((total,
                                        transaction) => total + transaction.quantity, 0);
                                }
                                return row.transaction.quantity - totalReturnedQuantity;
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                let totalReturnedQuantity = 0;
                                if (row.return_transactions && row.return_transactions.length > 0) {
                                    totalReturnedQuantity = row.return_transactions.reduce((total,
                                        transaction) => total + transaction.quantity, 0);
                                }
                                return totalReturnedQuantity;
                            }
                        },
                        {
                            "data": "quantity_lost"
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                if (row.status === "returned") {
                                    return `<span class="badge badge-linesuccess">Returned</span>`;
                                } else if (row.status === "borrowed") {
                                    return `<span class="badge badge-linewarning">Pending</span>`;
                                } else {
                                    return `<span class="badge badge-linewarning">Partially Returned</span>`;
                                }
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                return `
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2 partial-return-book" data-borrowid="${row.borrow_id}" data-schoolid="${row.transaction.reference_code.book_requests[0].school_id}" data-status="${row.status}">
                                        <i data-feather="clock" class="feather-clock"></i>
                                    </a>
                                    <a class="me-2 p-2 return-book" data-borrowid="${row.borrow_id}" data-schoolid="${row.transaction.reference_code.book_requests[0].school_id}" data-status="${row.status}">
                                        <i data-feather="check" class="feather-check"></i>
                                    </a>
                                </div>
                            `;
                            }
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').eq(7).addClass('action-table-data');
                    },
                    "initComplete": function(settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();

                        $('.status_filter, .book_filter, .school_filter').on('change', function() {
                            showLoader();
                            table.draw();
                        });
                    },
                    "drawCallback": function(settings) {
                        hideLoader();
                        feather.replace();
                    },
                    "preDrawCallback": function(settings) {
                        showLoader();
                    },
                });
            }

        });
    </script>
@endpush
