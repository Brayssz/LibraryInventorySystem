<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Transactions Report</title>
    <style>
        @font-face {
            font-family: 'OldEnglishTextMT';
            src: url('{{ asset('fonts/oldenglishtextmts.ttf') }}') format('truetype');
            font-weight: bold;
        }

        body {
            font-family: 'Bookman Old Style', serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .old_english {
            font-family: 'OldEnglishTextMT' !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 9px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .logo {
            text-align: center;
            margin-bottom: 7px;
            margin-top: -2px;
        }

        .logo img {
            width: 90px;
        }

        header {
            position: fixed;
            top: -270px;
            left: 0px;
            right: 0px;
            height: 100px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -85px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 10px;
            border-top: 2px solid #000;
        }

        @page {
            margin-top: 300px;
            margin-bottom: 140px;
        }

        .page-number:after {
            content: "Page " counter(page);
        }
    </style>
</head>

<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('img/deped_logo.png') }}" alt="Logo">
            </div>

            <p style="text-align: center; margin-bottom: -17px; font-size: 13px;" class="old_english">Republic of the Philippines</p>
            <p style="text-align: center; margin-bottom: -8px; font-size: 17px;" class="old_english">Department of Education</p>
            <p style="text-align: center; margin-bottom: -10px; letter-spacing: 4px;">SOCCSKSARGEN REGION</p>
            <p style="text-align: center; margin-bottom: 20px; letter-spacing: 4px;">SCHOOLS DIVISION OF KORONADAL CITY</p>

            <div style="border-top: 2px solid #000; margin: 20px 0;"></div>

            <h3 style="text-align: center; margin-top: 0px;">Delivery Transactions Report</h3>
            <h4 style="text-align: center; margin-bottom: 10px;">
                {{ \Carbon\Carbon::parse($startDate)->format('F j, Y') }} - 
                {{ \Carbon\Carbon::parse($endDate)->format('F j, Y') }}
            </h4>
        </div>
    </header>

    <footer>
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 33%; text-align: left; vertical-align: top; border: none;">
                    <img src="{{ public_path('img/deped-matatag-logos.png') }}" alt="Logo"
                        style="width: 140px; margin-right: 5px;">
                    <img src="{{ public_path('img/logo.jpg') }}" alt="Logo" style="width: 65px;">
                </td>
                <td
                    style="width: 57%; text-align: left; vertical-align: top; border: none; font-size: 11px; padding: 0px !important; font-family: Arial, sans-serif;">
                    <p style="margin: 1px 0;"><strong>Address:</strong> Jaycee Avenue, Corner Rizal St., Brgy. Zone IV,
                        City of Koronadal</p>
                    <p style="margin: 1px 0;"><strong>Telephone Nos:</strong> (083) 228-1209 / (083) 228-9706</p>
                    <p style="margin: 1px 0;"><strong>Email Address:</strong> Koronadal.city@deped.gov.ph</p>
                    <p style="margin: 1px 0;"><strong>Date Generated:</strong>
                        {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</p>
                </td>
                <td style="width: 10%; text-align: right; vertical-align: top; border: none; font-family: Arial, sans-serif;">
                    <div class="page-number"></div>
                </td>
            </tr>
        </table>
    </footer>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Reference Code</th>
                <th>Book Title</th>
                <th>Quantity</th>
                <th>Approved By</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $record)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $record['reference_code'] }}</td>
                    <td>{{ $record['book_title'] }}</td>
                    <td>{{ $record['quantity'] }}</td>
                    <td>{{ $record['approved_by'] }}</td>
                    <td>{{ $record['date'] }}</td>
                    <td>{{ $record['time'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No record found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
