<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>1stVault Cloud Services.</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />

    <!-- Invoice styling -->
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align: center;
            color: #777;
        }

        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 20px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06f;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

    </style>
</head>

<body>

    <h1>Dear {{ $user->name }}</h1>
    <h3>Your request has been recieved, Thank you for contacting us</h3><br /><br /><br />
    <b>Invoice #: {{ $invoice->id }}<br /></b>

    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ asset('images/logo.png') }}" alt="Company logo"
                                    style="width: 100%; max-width: 300px" />
                            </td>

                            <td>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                1stVault<br />
                                Janzour, Tripoli<br />
                                <a href="http://maps.google.com/maps?q=R2GW+WM2، جنزور"
                                    style="text-decoration: none">Location</a>
                            </td>

                            <td>
                                {{ $user->name }}<br />
                                {{ $user->email }}<br />
                                {{ $user->info->phone }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Payment Method</td>

                <td>Cash In Hand #</td>
            </tr>

            <tr class="details">
                <td>Cash</td>
                <td>{{ $invoice->total }}</td>
            </tr>

            <tr class="heading">
                <td>Item</td>
                <td>Duration</td>
                <td>Price Per Month</td>
                <td>Unit Total</td>
            </tr>
            @foreach ($invoice->items as $item)
                @if ($loop->last)
                    <tr class="item last">
                        <td>{{ $item->invoiceable->ar_name . ' / ' . $item->invoiceable->en_name }}</td>
                        <td>{{ $item->months . 'Months' }}</td>
                        <td>{{ $item->invoiceable->price }}</td>
                        <td>{{ $item->getTotal() }}</td>
                    </tr>
                @endif
                <tr class="item">
                    <td>{{ $item->invoiceable->ar_name . ' / ' . $item->invoiceable->en_name }}</td>
                    <td>{{ $item->months . 'Months' }}</td>
                    <td>{{ $item->invoiceable->price }}</td>
                    <td>{{ $item->getTotal() }}</td>
                </tr>
            @endforeach

            <tr class="total">
                <td></td>

                <td>Total: {{ $invoice->total }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
