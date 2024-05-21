<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
      *{
        margin:0;
        padding:0;
      }
        body{
          margin:0;
          padding:0;
          background-color:red;
          font-size:12px
        }
        #details{
            border-collapse: collapse;
            width: 100%;
        }
        #details th, td{
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

        .container{
          z-index: 9999;
        }
        p{
          margin:0px;
          padding:0px;
        }
    </style>
</head>
<body>

<div class="container">
  <h3 style="text-align:center">Bill</h3>


  <table style="width:100%; border:none">
    <tr>
      <td style="border:none;text-align:left; padding:0">Ref-{{$invoiceType->invoice_type_short_name}}{{date('y')}}{{date('m')}}{{$invoice['number']}}</td>
      <td style="border:none;text-align:right; padding:0">
        @if($invoice['date_visibility'])
        <span>Date: {{date("d-m-Y", strtotime($invoice['date'])) }}</span>
        @else
        <span>Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        @endif
      </td>
    </tr>
  </table>
  <br>
  <br>

  <p>To <br>
    The {{$customer->display_name}},<br>
    {{$customer->company_name}}, {{$customer->company_address}}.
  </p>
  

  <br>
  <p><b>Subject:</b> <span style="border-bottom:1px solid; padding-bottom:4px">{{$invoice['subject']}}</span></p>

  <br>
  <p>
  Dear Sir,<br>
  {{$invoice['description']}}
  </p>
  <br>
  <table id="details">
    <thead>
      <tr>
        <th>Sl No.</th>
        <th>Item Description</th>
        <th>Qty</th>
        <th>Unit</th>
        <th style="text-align:right">Unit Price</th>
        <th style="text-align:right">Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $beforedate=date_create($invoice['recurring_start_date']);
        $interval='1 days';
        date_sub($beforedate,date_interval_create_from_date_string($interval));
        $total=0;
      ?>
      @foreach($invoice_details as $invoice_detail)
      <tr>
        <td>{{$loop->iteration}}</td>
        <td>
          {{$invoice_detail->name}}<br>
          <?php
          $date=date_create($invoice['recurring_start_date']);
          $interval=$invoice['recurring_interval'].' days';
          date_add($date,date_interval_create_from_date_string($interval));

          //$recurring_start_date=date_add($date,date_interval_create_from_date_string("40 days"));
          //$recurring_start_date=date("d-m-Y", strtotime($invoice->recurring_start_date));
          //$futureDate=date($recurring_start_date, strtotime('+1 year'));
          ?>
          From {{date("d-m-Y", strtotime($invoice['recurring_start_date']))}} to
          {{date_format($date,"d-m-Y")}}
        </td>
        <td>{{$invoice_detail->quantity}}</td>
        <td>{{$invoice_detail->attributes['unit_name']}}</td>
        <td style="text-align:right">{{number_format((float)$invoice_detail->price, 2, '.', ',')}}</td>
        <td style="text-align:right">{{number_format((float)$invoice_detail->price*$invoice_detail->quantity, 2, '.', ',')}}</td>
      </tr>
      @php
      $total+=$invoice_detail->price*$invoice_detail->quantity;
      @endphp
      @endforeach
      
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5" style="text-align:center">Total</td>
        <td style="text-align:right">{{number_format((float)$total, 2, '.', ',')}}</td>
      </tr>

  
      @if($cartDiscount>0)
      <tr>
        <td colspan="5" style="text-align:center">Discount</td>
        <td style="text-align:right">{{number_format((float)$cartDiscount, 2, '.', ',')}}</td>
      </tr>
      @endif

      @if($cartVat>0)
      <tr>
        <td colspan="5" style="text-align:center">VAT ({{number_format((float)($cartVat*100)/$total, 2, '.', '')}} %)</td>
        <td style="text-align:right">{{number_format((float)$cartVat, 2, '.', ',')}}</td>
      </tr>
      @endif

      @if($cartTax>0)
      <tr>
        <td colspan="5" style="text-align:center">IT ({{number_format((float)($cartTax*100)/$total, 2, '.', '')}} %)</td>
        <td style="text-align:right">{{number_format((float)$cartTax, 2, '.', ',')}}</td>
      </tr>
      @endif

      <tr>
        <td colspan="5" style="text-align:center">Total Payable on/before date:{{date_format($beforedate,"d")}} {{date_format($beforedate,"F")}}, {{date_format($beforedate,"Y")}} </td>
        <td style="text-align:right">{{number_format((float)($total+$cartVat+$cartTax)-$cartDiscount, 2, '.', ',')}}</td>
      </tr>

      <tr>
        <td colspan="5" style="text-align:center">
          Payable after date:{{date_format($beforedate,"d")}} {{date_format($beforedate,"F")}}, {{date_format($beforedate,"Y")}} with late fee of Tk. 1000.00
        </td>
        <td style="text-align:right">{{number_format((float)($total+$cartVat+$cartTax)-$cartDiscount+1000, 2, '.', ',')}}</td>
      </tr>

      <!-- <tr>
        <td colspan="5" style="text-align:center">Grand Total</td>
        <td style="text-align:right">{{number_format((float)($total+$cartVat+$cartTax)-$cartDiscount, 2, '.', ',')}}</td>
      </tr> -->



    </tfoot>
  </table>
  <br>

  <?php
  $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
  echo '<p style="text-transform: capitalize; font-size:12px"><b>In Words: </b>'.$digit->format(($total+$cartVat+$cartTax)-$cartDiscount).' Taka Only.</p>';
  ?>

  @if(isset($invoice['note']))
  <h4>N.B: {{$invoice['vat_text_visibility']=="VAT & TAX. Paid by"?'VAT & TAX. Paid by '.$customer->company_name:$invoice['vat_text_visibility']}}</h4>
  @endif

  @if($invoice['vat_text_visibility']!="None" )
  <h4>N.B: {{$invoice['vat_text_visibility']}}</h4>
  @endif
  <h4>Payment Method:</h4>
  <ul>
    <li>Payment should be made by an account payee cheque in favor of "rajIT Solutions Ltd." A/C No. 0044-0210006856 Mutual Trust Bank Ltd, Rajshahi Branch. </li>
    <li>bkash at the number: 01762623195 (Merchant Account)/ 01755575801 (personal).</li>
  </ul>
  

  <br>
  <p style="margin-bottom:10px">Thanking you.</p>
  <p>Sincerely,</p>

  <br>
  <br>


  <table style="width:100%">
    @if(!is_null($employee->signature) && $invoice['auto_seal_signature'])
    <tr>
      <td style="width:35%;text-align:left; border:none;margin:0;padding:0;vertical-align:bottom">
          <img src="{{$employee->signature}}" height="40px">
      </td>
      <td rowspan="2" style="text-align:center; border:none; vertical-align:middle">
        <img src="/bg/seal.png" height="80px">
      </td>
    </tr>
    @endif
    <tr>
      <td style="border:none; text-align:left;margin:0;padding:0;vertical-align:top">
        <p><b>{{$employee->name}}</b></p>
        <p>{{$employee->designation->name}}</p>
      </td>
    </tr>
  </table>

</div>

</body>
</html>
