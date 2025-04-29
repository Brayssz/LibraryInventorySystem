@extends('layouts.app-layout')

@section('title', 'Inventory Management')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>DIVISION TOTAL</h4>
                    <h6>Review Division total</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw"
                            class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>

            <div class="page-btn">
                <a class="btn btn-added btn-excel"><i data-feather="file-text" class="me-2"></i>Download Excel Copy</a>
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

                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <select class="search-select book_div_filter form-control">
                                        <option value="">Book</option>
                                        @foreach ($l_books as $book)
                                            <option value="{{ $book->book_id }}">{{ $book->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table division-table pb-3">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Number of Copies Delivered</th>
                                <th>Actual Number of SLR's</th>
                                <th>Total Borrowed</th>
                                <th>Lost/Missing</th>
                                <th>Action</th>
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
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Schools Inventory</h4>
                    <h6>Manage Schools Inventory</h6>
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
                                        @foreach ($l_schools as $school)
                                            <option value="{{ $school->school_id }}">{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <select class="search-select book_filter form-control">
                                        <option value="">Book</option>
                                        @foreach ($l_books as $book)
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
                                <th>School</th>
                                <th>Book</th>
                                <th>Number of copies received</th>
                                <th>Available</th>
                                <th>Lost/Missing</th>
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
    @livewire('content.receive-delivery')
    {{-- @livewire('content.add-lost') --}}



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
                var bookTable = $('.book-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    'pagingType': 'numbers',
                    "ordering": true,
                    "order": [
                        [0, 'desc']
                    ],
                    "language": {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search...",
                        info: "_START_ - _END_ of _TOTAL_ items",
                    },
                    "ajax": {
                        "url": "/inventory",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function(d) {
                            d.school_id = $('.school_filter').val();
                            d.book_id = $('.book_filter').val();
                        },
                        "dataSrc": function(json) {
                            var data = [];
                            json.data.forEach(function(item) {
                                item.books.forEach(function(book) {
                                    data.push({
                                        book_photo_path: item.book_photo_path,
                                        book_id: item.book_id,
                                        school_id: item.school_id,
                                        inventory_id: item.inventory_id,
                                        school: item.school,
                                        title: book.title,
                                        received: book.received,
                                        available: book.quantity,
                                        lost: book.lost
                                    });
                                });
                            });
                            return data;
                        }
                    },
                    "columns": [{
                            "data": null,
                            "render": function(data, type, row) {
                                return row.school;
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                if (row.book_photo_path) {
                                    const avatarSrc = `/storage/${row.book_photo_path}`;
                                    return `
                                        <div class="userimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <img src="${avatarSrc}" alt="book cover" loading="lazy">
                                            </a>
                                            <div>
                                                <a href="javascript:void(0);">${row.title}</a>
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

                                    const firstLetter = row.title ? row.title.charAt(0)
                                    .toUpperCase() : 'A';
                                    const bgColor = colors[firstLetter] || 'bg-secondary';

                                    return `
                                        <div class="userimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <span class="avatar ${bgColor} avatar-rounded">
                                                    <span class="avatar-title">${firstLetter}</span>
                                                </span>
                                            </a>
                                            <div>
                                                <a href="javascript:void(0);">${row.title}</a>
                                            </div>
                                        </div>
                                    `;
                                }
                            }
                        },
                        {
                            "data": "received"
                        },
                        {
                            "data": "available"
                        },
                        {
                            "data": "lost"
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').eq(5).addClass('action-table-data');
                    },
                    "initComplete": function(settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();
                        hideLoader();

                        $('.book_filter, .school_filter').on('change', function() {
                            showLoader();
                            bookTable.ajax.reload();
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

            if ($('.division-table').length > 0) {
                var divisionTable = $('.division-table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    'pagingType': 'numbers',
                    "ordering": true,
                    "order": [
                        [0, 'desc']
                    ],
                    "language": {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search...",
                        info: "_START_ - _END_ of _TOTAL_ items",
                    },
                    "ajax": {
                        "url": "/division-total",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function(d) {
                            d.book_id = $('.book_div_filter').val();
                        },
                        "dataSrc": function(json) {
                            return json.data;
                        }
                    },
                    "columns": [{
                            "data": null,
                            "render": function(data, type, row) {
                                if (row.book_photo_path) {
                                    const avatarSrc = `/storage/${row.book_photo_path}`;
                                    return `
                                        <div class="userimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <img src="${avatarSrc}" alt="book cover" loading="lazy">
                                            </a>
                                            <div>
                                                <a href="javascript:void(0);">${row.title}</a>
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

                                    const firstLetter = row.title ? row.title.charAt(0)
                                    .toUpperCase() : 'A';
                                    const bgColor = colors[firstLetter] || 'bg-secondary';

                                    return `
                                        <div class="userimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <span class="avatar ${bgColor} avatar-rounded">
                                                    <span class="avatar-title">${firstLetter}</span>
                                                </span>
                                            </a>
                                            <div>
                                                <a href="javascript:void(0);">${row.title}</a>
                                            </div>
                                        </div>
                                    `;
                                }
                            }
                        },
                        {
                            "data": "total_received"
                        },
                        {
                            "data": "total_quantity"
                        },
                        {
                            "data": "total_available"
                        },
                        {
                            "data": "total_lost"
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                return `
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2 receive-delivery" data-bookid="${row.book_id}">
                                            <i data-feather="download" class="feather-download"></i>
                                        </a>
                                    </div>
                                `;
                            }
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').eq(5).addClass('action-table-data');
                    },
                    "initComplete": function(settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();

                        $('.book_div_filter').on('change', function() {
                            showLoader();
                            divisionTable.draw();
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

            $('.btn-excel').click(function() {
                showLoader();
                $.ajax({
                    url: 'generate-report',
                    type: 'GET',
                    success: function(response) {
                        var link = document.createElement('a');
                        link.href =
                            'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' +
                            response.fileContent;
                        link.download = response.fileName;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        hideLoader();
                    },
                    error: function() {
                        toastr.error("Failed to generate report", "Error", {
                            closeButton: true,
                            progressBar: true,
                        });
                        hideLoader();
                    }
                });

            });


        });
    </script>
@endpush
