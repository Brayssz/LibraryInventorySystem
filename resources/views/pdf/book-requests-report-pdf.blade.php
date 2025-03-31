<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Request Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 10px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        td:first-child {
            text-align: left;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
            position: absolute;
            top: 0px;
            left: 50px;
        }

        .logo img {
            width: 90px;
        }
    </style>
</head>

<body>
    <div class="logo">
        <img src="{{ public_path('img/logo.jpg') }}" alt="Logo">
    </div>

    <h4 style="text-align: center; margin-bottom: 10px;">Koronadal City Division - LRMDS</h4>
    <p style="text-align: center; margin-bottom: 5px;">Koronadal City, South Cotabato</p>
    <p style="text-align: center; margin-bottom: 20px;">Region XII</p>

    <div style="border-top: 1px solid #000; margin: 20px 0;"></div>

    <h3 style="text-align: center; margin-top: 20px;">Borrow Request Report</h3>
    <p style="text-align: center; margin-top: 0;">
        {{ \Carbon\Carbon::parse($startDate)->format('F j, Y') }} - 
        {{ \Carbon\Carbon::parse($endDate)->format('F j, Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Reference Code</th>
                <th>Book Title</th>
                <th>School Name</th>
                <th>Quantity</th>
                <th>Status</th>
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
                    <td>{{ $record['school_name'] }}</td>
                    <td>{{ $record['quantity'] }}</td>
                    <td>{{ $record['status'] }}</td>
                    <td>{{ $record['date'] }}</td>
                    <td>{{ $record['time'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No record found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        <h4 style="margin-bottom: 10px;">Prepared by:</h4>
        <h3 style="margin-bottom: 1px;">
            {{ Auth::user()->name }}
        </h3>
        <p style="font-size: 14px">Administrator</p>
        <p style="font-size: 14px">Generated on: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </div>
</body>

</html>
