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
    </style>
</head>

<body>
    {{-- {{dd($invoice)}} --}}

    <div class="container">
        <h3 style="text-align:center">Bill</h3>

        <table style="width:100%; border:none">
            <tr>
                <td style="border:none;text-align:left; padding:0">
                    Ref-{{ $invoice->invoice_type->invoice_type_short_name }}{{ date('y') }}{{ date('m') }}{{ $invoice->number }}
                </td>
                <td style="border:none;text-align:right; padding:0">
                    @if ($invoice->date_visibility)
                        <span>Date: {{ date('d-m-Y', strtotime($invoice->date)) }}</span>
                    @else
                        <span>Date:
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    @endif
                </td>
            </tr>
        </table>
        <br>
        <br>

        <p>To <br>
            The {{ $invoice->customer->display_name }},<br>
            {{ $invoice->customer->company_name }}, {{ $invoice->customer->company_address }}.
        </p>

        <br>
        <p><strong>Subject:</strong> <span
                style="border-bottom:1px solid; padding-bottom:4px; font-family:'siliguri','FreeSerif',sans-serif">{{ $invoice->subject }}</span>
        </p>

        <br>
        <p>
            Dear Sir,<br>
            <span style="font-family: 'siliguri','FreeSerif',sans-serif">{{ $invoice->description }}</span>
        </p>
        <br>
        <table id="details">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Item Description</th>
                    <th>Image</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th style="text-align:right">Unit Price</th>
                    <th style="text-align:right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $beforedate = date_create($invoice->recurring_start_date);
                $interval = '1 days';
                date_sub($beforedate, date_interval_create_from_date_string($interval));
                $total = 0;
                ?>
                @foreach ($invoice_details as $invoice_detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-family: 'siliguri','FreeSerif',sans-serif">
                            {{ $invoice_detail->product->name }}<br>
                            <?php
                            //$date=date_create($invoice->recurring_start_date);
                            $date = date_create($invoice->recurring_start_date);
                            $interval = $invoice->recurring_interval . ' days';
                            date_add($date, date_interval_create_from_date_string($interval));
                            
                            //$recurring_start_date=date_add($date,date_interval_create_from_date_string("40 days"));
                            //$recurring_start_date=date("d-m-Y", strtotime($invoice->recurring_start_date));
                            //$futureDate=date($recurring_start_date, strtotime('+1 year'));
                            
                            ?>
                            From {{ date('d-m-Y', strtotime($invoice->recurring_start_date)) }} to
                            {{ date_format($date, 'd-m-Y') }}
                        </td>
                        <td style="text-align:center">
                            @if ($invoice_detail->product->image)
                                <img height="60px" width="100px"
                                    src="{{ asset('storage/' . $invoice_detail->product->image) }}" alt="">
                            @endif
                        </td>
                        <td>{{ $invoice_detail->quantity }}</td>
                        <td>{{ $invoice_detail->product->unit->name }}</td>
                        <td style="text-align:right">{{ number_format((float) $invoice_detail->price, 2, '.', ',') }}
                        </td>
                        <td style="text-align:right">
                            {{ number_format((float) $invoice_detail->price * $invoice_detail->quantity, 2, '.', ',') }}
                        </td>
                    </tr>
                    @php
                        $total += $invoice_detail->price * $invoice_detail->quantity;
                    @endphp
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:center">Total</td>
                    <td style="text-align:right">{{ number_format((float) $total, 2, '.', ',') }}</td>
                </tr>


                @if ($invoice->discount > 0)
                    <tr>
                        <td colspan="6" style="text-align:center">Discount</td>
                        <td style="text-align:right">{{ number_format((float) $invoice->discount, 2, '.', ',') }}
                        </td>
                    </tr>
                @endif

                @if ($invoice->vat > 0)
                    <tr>
                        <td colspan="6" style="text-align:center">VAT
                            {{ number_format((float) (($invoice->vat * 100) / $invoice->sub_total), 2, '.', ',') }} %</td>
                        <td style="text-align:right">{{ number_format((float) $invoice->vat, 2, '.', ',') }}</td>
                    </tr>
                @endif

                @if ($invoice->tax > 0)
                    <tr>
                        <td colspan="6" style="text-align:center">TAX
                            {{ number_format((float) (($invoice->tax * 100) / $invoice->sub_total), 2, '.', ',') }} %</td>
                        <td style="text-align:right">{{ number_format((float) $invoice->tax, 2, '.', ',') }}</td>
                    </tr>
                @endif

                <tr>
                    <td colspan="6" style="text-align:center">Total Payable on/before
                        date:{{ date_format($beforedate, 'd') }}
                        {{ date_format($beforedate, 'F') }}, {{ date_format($beforedate, 'Y') }} </td>
                    <td style="text-align:right">{{ number_format((float) $invoice->total, 2, '.', ',') }}</td>
                </tr>

                <tr>
                    <td colspan="6" style="text-align:center">
                        Payable after date:{{ date_format($beforedate, 'd') }} {{ date_format($beforedate, 'F') }},
                        {{ date_format($beforedate, 'Y') }} with late fee of Tk. 1000.00
                    </td>
                    <td style="text-align:right">{{ number_format((float) $invoice->total + 1000, 2, '.', ',') }}</td>
                </tr>

                <!-- <tr>
        <td colspan="6" style="text-align:center">Grand Total</td>
        <td style="text-align:right">{{ number_format((float) $invoice->total, 2, '.', ',') }}</td>
      </tr> -->



            </tfoot>
        </table>
        <br>

        <?php
        $digit = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        echo '<p style="text-transform: capitalize; font-size:12px"><b>In Words: </b>' . $digit->format($invoice->total) . ' Taka Only.</p>';
        ?>

        @if (isset($invoice->note))
            <h4>N.B: {{ $invoice->note }}</h4>
        @endif

        {{-- @if ($invoice->vat_text_visibility != 'None')
    <h4>N.B: {{$invoice->vat_text_visibility=="VAT & TAX. Paid by"?'VAT & TAX. Paid by
      '.$invoice->customer->company_name:$invoice->vat_text_visibility}}</h4>
    @endif --}}

        @if ($invoice->note != 'None')
            <h4>N.B: {{ $invoice->note }}</h4>
        @endif

        <h4>Payment Method:</h4>
        <ul>
            <li>Payment should be made by an account payee cheque in favor of "North Bengal Engineering" A/C No.
                20502790100166302 Rut=125811637 Islami Bank Ltd. </li>
            <li>bkash at the number: 01772176237 (personal).</li>
        </ul>


        <br>
        <p style="margin-bottom:10px">Thanking you.</p>
        <p>Sincerely,</p>

        <br>
        <br>


        <table style="width:100%">
            @if (!is_null($employee->signature) && $invoice->auto_seal_signature)
                <tr>
                    <td style="width:35%;text-align:left; border:none;margin:0;padding:0;vertical-align:bottom">
                        <img src="{{ $employee->signature }}" height="40px">
                    </td>
                    <td rowspan="2" style="text-align:center; border:none; vertical-align:middle">
                        <img src="/bg/seal.png" height="90px">
                    </td>
                </tr>
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
