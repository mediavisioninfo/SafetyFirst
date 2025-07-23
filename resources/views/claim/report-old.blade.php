<!DOCTYPE html>
<html>

<head>
    <title>Claim Report</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        h3 {
            font-size: 20px;
            color: #34495e;
            margin-top: 30px;
        }

        .section {
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #bdc3c7;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #ecf0f1;
            color: #34495e;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .grid-item {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .highlight {
            background-color: #f39c12;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            background-color: #ecf0f1;
        }

        /* Header Styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 2px solid #bdc3c7;
        }

        .header-logo-left {
            display: flex;
            align-items: center;
        }

        .header-logo-left img {
            height: 80px;
            margin-right: 20px;
        }

        .header-text {
            text-align: center;
            flex-grow: 1;
        }

        .header-text h1 {
            font-size: 20px;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
        }

        .header-text h2 {
            font-size: 16px;
            color: #7f8c8d;
            font-weight: normal;
            margin: 0;
        }

        .header-text h3 {
            font-size: 12px;
            color: #34495e;
            font-weight: normal;
            margin-top: 5px;
            font-style: italic;
        }

        .header-logo-right img {
            height: 40px;
        }

        /* Report Info Styling */
        .report-info {
            margin: 2px 0;
            padding: 15px;
            background-color: #ecf0f1;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            /* Aligns items left and right */
            align-items: center;
            /* Vertically center items */
        }

        .report-number-wrapper,
        .report-date-wrapper {
            font-size: 18px;
            color: #2c3e50;
        }

        .report-number {
            font-weight: bold;
        }

        .report-date {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <!-- Left Logo -->
        <div class="header-logo-left">
            <img src="{{ asset('path/to/left-logo.png') }}" alt="Left Logo">
        </div>

        <!-- Header Text -->
        <div class="header-text">
            <h1>MOTOR (FINAL) SURVEY REPORT</h1>
            <h3>Private & Confidential</h3>
            <h3 style="border-bottom: 2px solid #2c3e50;">"Issued Without Prejudice"</h3>
        </div>

        <div class="report-info">
            <div class="report-number-wrapper">
                Report No: <span class="report-number">{{ $claim->claim_id }}</span>
            </div>
            <div class="report-date-wrapper">
                Date:
                <span class="report-date">
                    {{ date('d-m-Y', strtotime($claim->date)) }}
                </span>
            </div>
        </div>

    </div>

    <!-- Report Info Section -->


    <table>
        <thead>
            <tr>
                <th colspan="4">Claim Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Vehicle Number:</td>
                <td>{{ $claim->vehicle_number }}</td>
                <td>Customer ID:</td>
                <td>{{ $claim->customer }}</td>
            </tr>
            <tr>
                <td>Status:</td>
                <td class="highlight">{{ ucfirst($claim->status) }}</td>
                <td>Reason:</td>
                <td>{{ $claim->reason }}</td>
            </tr>
            <tr>
                <td>Claim Amount:</td>
                <td>&#8377; {{ number_format($claim->claim_amount, 2) }}</td>
                <td>Policy Number:</td>
                <td>{{ $claim->policy_number }}</td>
            </tr>
            <tr>
                <td>Contact:</td>
                <td>{{ $claim->mobile }} </td>
                <td>Email:</td>
                <td>{{ $claim->email }}</td>
            </tr>
            <tr>
                <td>Notes:</td>
                <td class="highlight" colspan="3">{{ $claim->notes }}</td>
            </tr>
        </tbody>
    </table>

    @if (isset($ocrResults['rcbook']))
        <table>
            <thead>
                <tr>
                    <th colspan="2">Vehicle Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ocrResults['rcbook'] as $filePath => $info)
                    @foreach ($info['text'] as $key => $value)
                        <tr>
                            <td><strong>{{ $key }}:</strong></td>
                            <td>
                                @if ($key === 'Address')
                                    {{ $value }}
                                @else
                                    {{ strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Details of Damage</h2>
    <table>
        <thead>
            <tr>
                <th>Part</th>
                <th>Price (&#8377;)</th>
                <th>Score</th>
                <th>Severity</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPrice = 0; @endphp
            @foreach ($damageResults as $result)
                <tr>
                    <td>{{ $result['class'] }}</td>
                    <td>{{ number_format($result['price'], 2) }}</td>
                    <td>{{ number_format($result['score'], 2) }}</td>
                    <td>{{ ucfirst($result['severity']) }}</td>
                </tr>
                @php $totalPrice += $result['price']; @endphp
            @endforeach
            @php
                $gst = $totalPrice * 0.18;
                $totalWithGst = $totalPrice + $gst;
            @endphp
            <tr class="total-row">
                <td><strong>Total Estimation</strong></td>
                <td><strong>&#8377; {{ number_format($totalPrice, 2) }}</strong></td>
                <td colspan="2"></td>
            </tr>
            <tr class="total-row">
                <td><strong>GST (18%)</strong></td>
                <td><strong>&#8377; {{ number_format($gst, 2) }}</strong></td>
                <td colspan="2"></td>
            </tr>
            <tr class="total-row">
                <td><strong>Total with GST</strong></td>
                <td><strong>&#8377; {{ number_format($totalWithGst, 2) }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

</body>

</html>
