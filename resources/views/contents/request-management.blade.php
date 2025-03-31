@extends('layouts.app-layout')

@section('title', 'Request Management')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Book Requests</h4>
                    <h6>Manage your book requests</h6>
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
        </div>
        <!-- /request list -->
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
                                    <select class="select book_filter form-control">
                                        <option value="">Book</option>
                                        @foreach ($books as $book)
                                            <option value="{{ $book->book_id }}">{{ $book->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

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
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table request-table pb-3">
                        <thead>
                            <tr>
                                <th>Reference Code</th>
                                <th>School</th>
                                <th>Book</th>
                                <th>Request Quantity</th>
                                <th>Released Quantity</th>
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
    @livewire('content.request-management')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            @if (session('message'))
                toastr.success("{{ session('message') }}", "Success", {
                    closeButton: true,
                    progressBar: true,
                });
            @endif

            if ($('.request-table').length > 0) {
                var table = $('.request-table').DataTable({
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
                        "url": "/book-request",
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
                            "data": "reference_code.reference_code"
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

                                const firstLetter = row.school.name ? row.school.name.charAt(0)
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
                                            <a href="javascript:void(0);">${row.school.name}</a>
                                        </div>
                                    </div>
                                `;

                            }
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

                                const firstLetter = row.book.title ? row.book.title.charAt(0)
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
                                            <a href="javascript:void(0);">${row.book.title}</a>
                                        </div>
                                    </div>
                                `;

                            }
                        },
                        {
                            "data": "quantity"
                        },
                        {
                            "data": "quantity_released"
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                return row.status === "approved" ?
                                    `<span class="badge badge-linesuccess">Approved</span>` :
                                    row.status === "rejected" ?
                                    `<span class="badge badge-linedanger">Rejected</span>` :
                                    `<span class="badge badge-linewarning">Pending</span>`;
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                return `
                                <div class="edit-delete-action">
                                    <a class="me-2 p-2 approve-request" data-requestid="${row.request_id}" data-status="${row.status}">
                                        <i data-feather="check" class="feather-check"></i>
                                    </a>
                                    <a class="me-2 p-2 reject-request" data-requestid="${row.request_id}" data-status="${row.status}">
                                        <i data-feather="x" class="feather-x"></i>
                                    </a>
                                </div>
                            `;
                            }
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').eq(6).addClass('action-table-data');
                    },
                    "initComplete": function(settings, json) {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter').appendTo('.search-input');
                        feather.replace();
                        hideLoader();

                        $('.status_filter, .book_filter, .school_filter').on('change', function() {
                            console.log('change');
                            table.draw();
                        });
                    },
                    "drawCallback": function(settings) {
                        feather.replace();
                    },
                });


            }

        });
    </script>
@endpush
