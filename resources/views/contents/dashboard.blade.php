@extends('layouts.app-layout')

@section('title', 'Dashboard')

@section('content')

    <div class="content">
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-users" style="color: #ffc107; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_users }}">{{ $total_users }}</span>
                        </h5>
                        <h6>Total Users</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash1 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-book" style="color: #28C76F; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_books }}">{{ $total_books }}</span>
                        </h5>
                        <h6>Total Books</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash2 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-school" style="color: #007bff; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_schools }}">{{ $total_schools }}</span>
                        </h5>
                        <h6>Total Schools</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash3 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-book-reader" style="color: #dc3545; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_requests }}">{{ $total_requests }}</span>
                        </h5>
                        <h6>Total Pending Book Request</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-undo" style="color: #ffc107; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_pending_return }}">{{ $total_pending_return }}</span>
                        </h5>
                        <h6>Total Pending to Return</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-exchange-alt" style="color: #d4ed00; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_partially_returned }}">{{ $total_partially_returned }}</span>
                        </h5>
                        <h6>Total Partially Returned</h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget dash3 w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-clock" style="color: #dc3545; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_due }}">{{ $total_due }}</span>
                        </h5>
                        <h6>Total Overdue Transactions</h6>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-6 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Monthly Transactions</h5>
                        <div class="graph-sets">
                            <ul class="mb-0">
                                <li>
                                    <span>Borrowed</span>
                                </li>
                                <li>
                                    <span>Returned</span>
                                </li>
                                <li>
                                    <span>Lost</span>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="card-body">
                        <div id="application_charts"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-sm-12 col-12 d-flex">
                <div class="card flex-fill default-cover mb-4" style="max-height: 450px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Top Borrowed Books</h4>
                        <div class="view-all-link">
                            <a href="inventory" class="view-all d-flex align-items-center">
                                View Inventory<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                        class="feather-16"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview" style="max-height: 350px; overflow-y: auto;">
                            @if ($top_borrowed_books->isEmpty())
                                <div class="text-center mt-4">
                                    <i class="fa fa-calendar-times" style="font-size: 2rem; color: #dc3545;"></i>
                                    <p class="mt-4">No borrowed books data available.</p>
                                </div>
                            @else
                                <table class="table table-responsive dashboard-recent-products">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book Title</th>
                                            <th>Borrowed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($top_borrowed_books as $book)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="d-flex align-items-center">
                                                    @php
                                                        $bookPhotoPath = $book->book_photo_path ? asset('storage/' . $book->book_photo_path) : null;
                                                    @endphp

                                                    @if ($bookPhotoPath)
                                                        <img src="{{ $bookPhotoPath }}" alt="book cover" loading="lazy" style="height: 2.65rem;" class="rounded-2 me-2">
                                                    @else
                                                        @php
                                                            $bookFirstLetter = strtoupper(substr($book->title, 0, 1));
                                                            $bgColor = $colors[$bookFirstLetter] ?? 'bg-secondary';
                                                        @endphp
                                                        <span class="avatar {{ $bgColor }} avatar-rounded me-2" style="height: 2.65rem;">
                                                            <span class="avatar-title">{{ $bookFirstLetter }}</span>
                                                        </span>
                                                    @endif

                                                    <div>
                                                        <a>{{ $book->title }}</a>
                                                        <span class="emp-team d-block text-muted" style="font-size: 0.80rem;">Copyright &copy; {{ \Carbon\Carbon::parse($book->published_date)->format('Y') }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $book->total_borrowed }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-7 col-sm-12 col-12 d-flex">
				<div class="card flex-fill default-cover mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Pending Request</h4>
                        <div class="view-all-link">
                            <a href="book-request" class="view-all d-flex align-items-center">
                                View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                        class="feather-16"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            @if ($pending_requests->isEmpty())
                                <div class="text-center mt-4">
                                    <i class="fa fa-calendar-times" style="font-size: 2rem; color: #dc3545;"></i>
                                    <p class="mt-4">No pending requests available.</p>
                                </div>
                            @else
                                <table class="table dashboard-recent-products">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>School</th>
                                            <th>Book</th>
                                            <th>Requested Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $colors = [
                                                'A' => 'bg-primary',
                                                'B' => 'bg-success',
                                                'C' => 'bg-danger',
                                                'D' => 'bg-warning',
                                                'E' => 'bg-info',
                                                'F' => 'bg-dark',
                                                'G' => 'bg-secondary',
                                                // Add more mappings as needed
                                            ];
                                        @endphp

                                        @foreach ($pending_requests as $pending_request)
											<tr>
												<td>{{ $loop->iteration }}</td>
                                                <td>{{ $pending_request->school->name }}</td>
												<td>
													@php
														$book = $pending_request->book;
														$bookPhotoPath = $book->book_photo_path ? asset('storage/' . $book->book_photo_path) : null;
													@endphp
										
													@if ($bookPhotoPath)
														<img src="{{ $bookPhotoPath }}" alt="book cover" loading="lazy" style="height: 2.65rem;" class="rounded-2">
													@else
														@php
															$bookFirstLetter = strtoupper(substr($book->title, 0, 1));
															$bgColor = $colors[$bookFirstLetter] ?? 'bg-secondary';
														@endphp
														<span class="avatar {{ $bgColor }} avatar-rounded" style="height: 2.65rem;">
															<span class="avatar-title">{{ $bookFirstLetter }}</span>
														</span>
													@endif
										
													<a>{{ $book->title }}</a>
												</td>
										
												<td>{{ $pending_request->quantity }}</td>
											</tr>
										@endforeach
										
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-sm-12 col-12 d-flex">
                <div class="card flex-fill default-cover mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Overdue Transactions</h4>
                        <div class="view-all-link">
                            <a href="borrowed-books" class="view-all d-flex align-items-center">
                                View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right"
                                        class="feather-16"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            @if ($overdueDetails->isEmpty())
                                <div class="text-center mt-4">
                                    <i class="fa fa-calendar-times" style="font-size: 2rem; color: #dc3545;"></i>
                                    <p class="mt-4">No overdue requests available.</p>
                                </div>
                            @else
                                <table class="table dashboard-recent-products">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>School</th>
                                            <th>Book</th>
                                            <th>Days Due</th>
                                            <th>Requested Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $colors = [
                                                'A' => 'bg-primary',
                                                'B' => 'bg-success',
                                                'C' => 'bg-danger',
                                                'D' => 'bg-warning',
                                                'E' => 'bg-info',
                                                'F' => 'bg-dark',
                                                'G' => 'bg-secondary',
                                                // Add more mappings as needed
                                            ];
                                        @endphp
                                        @foreach ($overdueDetails as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                        
                                                <td>{{ $detail['school'] }}</td>
                                        
                                                <td>
                                                    @php
                                                        $bookTitle = $detail['book'];
                                                        $bookFirstLetter = strtoupper(substr($bookTitle, 0, 1));
                                                        $bgColor = $colors[$bookFirstLetter] ?? 'bg-secondary';
                                                    @endphp
                                        
                                                    <span class="avatar {{ $bgColor }} avatar-rounded" style="height: 2.65rem;">
                                                        <span class="avatar-title">{{ $bookFirstLetter }}</span>
                                                    </span>
                                                    <a>{{ $bookTitle }}</a>
                                                </td>
                                        
                                                <td>{{ $detail['time_due'] }}</td>
                                                <td>{{ $detail['requested_qty'] }}</td>
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            if ($("#application_charts").length > 0) {
                $.ajax({
                    url: "/monthly-transaction-data", // Laravel route
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        var maxBorrowed = Math.max(...response.borrowed.map(Number));
                        var maxReturned = Math.max(...response.returned.map(Number));
                        var maxLost = Math.max(...response.lost.map(Number));
                        var maxY = Math.max(maxBorrowed, maxReturned, maxLost);

                        var options = {
                            series: [
                                {
                                    name: "Borrowed",
                                    data: response.borrowed.map(Number),
                                },
                                {
                                    name: "Returned",
                                    data: response.returned.map(Number),
                                },
                                {
                                    name: "Lost",
                                    data: response.lost.map(Number),
                                },
                            ],
                            colors: ["#28C76F", "#007bff", "#EA5455"],
                            chart: {
                                type: "bar",
                                height: 320,
                                zoom: {
                                    enabled: true
                                },
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    borderRadius: 4,
                                    columnWidth: "50%",
                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            yaxis: {
                                min: 0,
                                max: maxY,
                                tickAmount: 5
                            },
                            xaxis: {
                                categories: [
                                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                ],
                            },
                            legend: {
                                show: false
                            },
                            fill: {
                                opacity: 1
                            },
                        };

                        var chart = new ApexCharts(
                            document.querySelector("#application_charts"),
                            options
                        );
                        chart.render();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            }
        });
    </script>
@endpush
