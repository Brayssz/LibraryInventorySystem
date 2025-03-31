<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Inventory Report</title>
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

    <h3 style="text-align: center; margin-top: 20px;">Book Inventory Report</h3>
    <p style="text-align: center; margin-top: 0;">
        {{ \Carbon\Carbon::parse($startDate)->format('F j, Y') }} - 
        {{ \Carbon\Carbon::parse($endDate)->format('F j, Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Book Title</th>
                <th>Quantity Delivered</th>
                <th>Quantity on Division</th>
                <th>Quantity Borrowed</th>
                <th>Quantity Returned</th>
                <th>Quantity Lost</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $record)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $record['book_title'] }}</td>
                    <td>{{ $record['quantity_delivered'] }}</td>
                    <td>{{ $record['quantity_on_division'] }}</td>
                    <td>{{ $record['quantity_borrowed'] }}</td>
                    <td>{{ $record['quantity_returned'] }}</td>
                    <td>{{ $record['quantity_lost'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No record found</td>
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
