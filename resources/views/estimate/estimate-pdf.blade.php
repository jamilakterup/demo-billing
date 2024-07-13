<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: red;
            font-size: 12px
        }

        #details {
            border-collapse: collapse;
            width: 100%;
        }

        #details th,
        td {
            border: 1px solid #212529;
            padding: 4px 10px;
        }

        #details th {
            background-color: #ced4da;
            color: #212529;

        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        .container {

            z-index: 9999;
        }

        p {
            margin: 0px;
            padding: 0px;
        }

        .seal_signature {
            position: relative;
            width: 100px;
            height: 80px;
        }

        .image1 {
            position: absolute;
            z-index: 1;
            height: 40px;
            margin-left: -70px;
            margin-bottom: 20px;
        }

        .image2 {
            position: absolute;
            z-index: 0;
            height: 80px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h4 style="text-align:center"><span style="padding-bottom:5px; border-bottom:1px solid">Quotation</span></h4>


        <table style="width:100%; border:none">
            <tr>
                <td style="border:none;text-align:left; padding:0">
                    Ref-{{ $estimate->quotation_type->quotation_type_short_name }}{{ date('y') }}{{ date('m') }}{{ $estimate->number }}
                </td>
                <td style="border:none;text-align:right; padding:0">
                    @if ($estimate->date_visibility)
                        <span>Date: {{ date('d-m-Y', strtotime($estimate->estimate_date)) }}</span>
                    @else
                        <span>Date:
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    @endif
                </td>
            </tr>
        </table>
        <br>

        <p style="line-height:2">To <br>

            <b>The {{ $estimate->customer->display_name }},</b><br>
            {{ $estimate->customer->company_name }}, {{ $estimate->customer->company_address }}.
        </p>

        <br>

        <p><strong>Subject:</strong> <span
                style="border-bottom:1px solid; padding-bottom:4px; font-family:'siliguri','FreeSerif',sans-serif">{{ $estimate->subject }}</span>
        </p>
        <br>

        <p style="line-height:2">
            Dear Sir,<br>
            <span style="font-family: 'siliguri','FreeSerif',sans-serif">{{ $estimate->description }}</span>
        </p>
        <br>
        <table id="details">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Item</th>
                    <th>Image</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th style="text-align:right">Unit Price</th>
                    <th style="text-align:right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $vat = 0;
                    $tax = 0;
                @endphp
                @foreach ($estimate_details as $estimate_detail)
                    {{-- {{dd($estimate_details)}} --}}
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-family: 'siliguri','FreeSerif',sans-serif">
                            {{ $estimate_detail->product->name }}</td>
                        <td style="text-align:center">
                            @if ($estimate_detail->product->image)
                                <img height="60px" width="100px"
                                    src="{{ asset('storage/' . $estimate_detail->product->image) }}" alt="">
                            @endif
                        </td>
                        <td>{{ $estimate_detail->quantity }}</td>
                        <td>{{ $estimate_detail->product->unit->name }}</td>
                        <td style="text-align:right">{{ number_format((float) $estimate_detail->price, 2, '.', ',') }}
                        </td>
                        <td style="text-align:right">
                            {{ number_format((float) $estimate_detail->price * $estimate_detail->quantity, 2, '.', ',') }}
                        </td>
                    </tr>
                    @php
                        $total += $estimate_detail->price * $estimate_detail->quantity;
                        $vat = number_format(((float) $estimate->total * $estimate->vat) / 100, 2, '.', '');
                        $tax = number_format(((float) $estimate->total * $estimate->tax) / 100, 2, '.', '');
                        $grandTotal = $estimate->sub_total + $vat + $tax;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:center">Total</td>
                    <td style="text-align:right">{{ number_format((float) $total, 2, '.', ',') }}</td>
                </tr>
                @if ($estimate->discount > 0)
                    <tr>
                        <td colspan="6" style="text-align:center">Discount</td>
                        <td style="text-align:right">{{ number_format((float) $estimate->discount, 2, '.', ',') }}
                        </td>
                    </tr>
                @endif

                @if ($estimate->vat > 0)
                    <tr>
                        <td colspan="6" style="text-align:center">VAT
                            {{ number_format((float) (($estimate->vat * 100) / $estimate->sub_total), 2, '.', ',') }} %
                        </td>
                        <td style="text-align:right">{{ number_format((float) $estimate->vat, 2, '.', ',') }}</td>
                    </tr>
                @endif

                @if ($estimate->tax > 0)
                    <tr>
                        <td colspan="6" style="text-align:center">TAX
                            {{ number_format((float) (($estimate->tax * 100) / $estimate->sub_total), 2, '.', ',') }} %
                        </td>
                        <td style="text-align:right">{{ number_format((float) $estimate->tax, 2, '.', ',') }}</td>
                    </tr>
                @endif

                <tr>
                    <td colspan="6" style="text-align:center">Grand Total</td>
                    <td style="text-align:right">{{ number_format((float) $grandTotal, 2, '.', ',') }}</td>
                </tr>
            </tfoot>
        </table>
        <br>

        <?php
        $digit = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        echo '<p style="text-transform: capitalize; font-size:12px"><b>In Words: </b>' . $digit->format($grandTotal) . ' Taka Only.</p>';
        ?>

        @if (isset($estimate->vat_text_visibility))
            <h4>N.B: {{ $estimate->note }}</h4>
        @endif
        <br>
        <p>Thanking you.</p>
        <p>Sincerely,</p>

        <br>
        <br>


        <table style="width:100%">
            @if (!is_null($employee->signature) && $estimate->auto_seal_signature)
                <tr>
                    <td style="width:35%;text-align:left; border:none;margin:0;padding:0;vertical-align:bottom">
                        <div class="seal_signature">
                            <img src="bg/seal.png" class="image2">
                            <img src="{{ $employee->signature }}" class="image1">
                        </div>
                    </td>
                </tr>
                {{-- <tr>
                    <td style="width:35%;text-align:left; border:none;margin:0;padding:0;vertical-align:bottom">
                        <img src="{{ $employee->signature }}" height="40px">
                    </td>
                    <td rowspan="2" style="text-align:center; border:none; vertical-align:middle">
                        <img src="/bg/seal.png" height="90px">
                    </td>
                </tr> --}}
            @endif
            <tr>
                <td style="border:none; text-align:left;margin:0;padding:0;vertical-align:top">
                    <p><b>{{ $employee->name }}</b></p>
                    <p>{{ $employee->designation->name }}</p>
                </td>
            </tr>
        </table>


    </div>

</body>

</html>
