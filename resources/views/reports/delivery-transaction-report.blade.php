@extends('layouts.app-layout')

@section('title', 'Delivery Transaction Report')

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Delivery Transaction Report</h4>
                    <h6>Monitor the delivery transactions</h6>
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
                <a class="btn btn-added btn-generate"><i data-feather="plus-circle" class="me-2"></i>Generate Report</a>
            </div>
        </div>

        <!-- /product list -->
        <div class="card table-list-card">
            <div class="card-body pb-0">
                <div class="table-top table-top-two table-top-new d-flex ">
                    <div class="search-set mb-0 d-flex w-100 justify-content-start">

                        <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1">
                            <div class="col-lg-4 col-sm-12">
                                <div class=" position-relative">
                                    <input type="text" id="reportrange" class="form-control pe-5 daterange_filter"
                                        placeholder="Select Date Range" readonly>
                                    <i class="far fa-calendar position-absolute top-50 end-0 translate-middle-y pe-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table report-table pb-3">
                        <thead>
                            <tr>
                                <th>Reference Code</th>
                                <th>Book</th>
                                <th>Quantity</th>
                                <th>Approved By</th>
                                <th>Date</th>
                                <th>Time</th>
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

@endsection
@push('scripts')
    <script>
        $(document).ready(function () {

            initSelect();

            @if (session('message'))
                toastr.success("{{ session('message') }}", "Success", {
                    closeButton: true,
                    progressBar: true,
                });
            @endif

            $('.btn-generate').on('click', function () {
                window.open('/generate-delivery-transactions-report?date_range=' + $('.daterange_filter').val(), '_blank');
            });

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            if ($('.report-table').length > 0) {
                var table = $('.report-table').DataTable({
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
                        "url": "/delivery-transactions-report",
                        "type": "GET",
                        "headers": {
                            "Accept": "application/json"
                        },
                        "data": function (d) {
                            d.date_range = $('.daterange_filter').val();
                        },
                        "dataSrc": "data"
                    },
                    "columns": [
                        {
                            "data": "reference_code"
                        },
                        {
                            "data": "book_title"
                        },
                        {
                            "data": "quantity"
                        },
                        {
                            "data": "approved_by"
                        },
                        {
                            "data": "date"
                        },
                        {
                            "data": "time"
                        },
                    ],
                    "initComplete": function (settings, json) {
                        $('.dataTables_filter').remove();
                        feather.replace();

                        $('.daterange_filter').on('change', function () {
                            table.draw();
                        });
                    },
                    "drawCallback": function (settings) {
                        feather.replace();
                    },
                });
            }
        });

        function initSelect() {
            $('.select').select2({
                minimumResultsForSearch: -1,
                width: '100%'
            });
        }
    </script>
@endpush
