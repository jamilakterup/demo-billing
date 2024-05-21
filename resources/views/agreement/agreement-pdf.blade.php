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

  <div class="container">
    <h4 style="text-align:center"><span style="padding-bottom:5px; border-bottom:1px solid">Agreement</span></h4>


    <table style="width:100%; border:none">
      <tr>
        <td style="border:none;text-align:left; padding:0">
          Ref-{{$agreement->agreement_type->agreement_type_short_name}}{{date('y')}}{{date('m')}}{{$agreement->number}}
        </td>
        <td style="border:none;text-align:right; padding:0">
          Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <!-- <td style="border:none;text-align:right; padding:0">Date: {{date("d-m-Y", strtotime($agreement->agreement_date)) }}</td> -->
      </tr>
    </table>
    <br>

    <p style="line-height:2">To <br>

      <b>The {{$agreement->customer->display_name}},</b><br>
      {{$agreement->customer->company_name}}, {{$agreement->customer->company_address}}.
    </p>

    <br>

    <p><b>Subject:</b> <span
        style="border-bottom:1px solid; padding-bottom:4px">{{$agreement->agreement_type->subject}}</span></p>
    <br>

    <p style="line-height:2">
      Dear Sir,<br>
      {{$agreement->agreement_type->description}}
    </p>
    <br>
    <table id="details">
      <thead>
        <tr>
          <th>Sl No.</th>
          <th>Item</th>
          <th>Description</th>
          <th>Image</th>
          <th>Qty</th>
          <th>Unit</th>
          <th style="text-align:right">Unit Price</th>
          <th style="text-align:right">Amount</th>
        </tr>
      </thead>
      <tbody>


        @foreach($agreement_details as $agreement_detail)
        <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$agreement_detail->product->name}}</td>
          <td>{{$agreement_detail->product->description}}</td>
          @if ($agreement_detail->product->image)
          <td class="text-center">
            <img style="height:75px; width:95px" src="{{ asset('storage/' . $agreement_detail->product->image) }}"
              alt="product image">
          </td>
          @endif
          <td>{{$agreement_detail->quantity}}</td>
          <td>{{$agreement_detail->product->unit->name}}</td>
          <td style="text-align:right">{{number_format((float)$agreement_detail->price, 2, '.', '')}}</td>
          <td style="text-align:right">{{number_format((float)$agreement_detail->price*$agreement_detail->quantity, 2,
            '.', '')}}</td>
        </tr>
        @endforeach

      </tbody>
      <tfoot>

        <?php
            $grandTotal=$agreement->agreement_details->sum('total');
            $grandTotal=($grandTotal-$agreement->discount)+$agreement->vat+$agreement->tax;
        ?>


        @if($agreement->discount>0)
        <tr>
          <td colspan="7" style="text-align:center">Discount</td>
          <td style="text-align:right">{{number_format((float)$agreement->discount, 2, '.', '')}}</td>
        </tr>
        @endif

        @if($agreement->vat>0)
        <tr>
          <td colspan="7" style="text-align:center">Vat</td>
          <td style="text-align:right">{{number_format((float)$agreement->vat, 2, '.', '')}}</td>
        </tr>
        @endif

        @if($agreement->tax>0)
        <tr>
          <td colspan="7" style="text-align:center">Tax</td>
          <td style="text-align:right">{{number_format((float)$agreement->tax, 2, '.', '')}}</td>
        </tr>
        @endif

        <tr>
          <td colspan="7" style="text-align:center">Total Amount</td>
          <td style="text-align:right">{{number_format((float)$grandTotal, 2, '.', '')}}</td>
        </tr>
      </tfoot>
    </table>
    <br>

    <?php
  $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
  echo '<p style="text-transform: capitalize; font-size:12px"><b>In Words: </b>'.$digit->format($grandTotal).' Taka Only.</p>';
  ?>

    @if(isset($agreement->note))
    <h4>N.B: {{$agreement->note}}</h4>
    @endif
    <br>
    <p>Thanking you.</p>
    <p>Sincerely,</p>

    <br>
    <br>


    <table style="width:100%">
      @if(!is_null($employee->signature) && $agreement->auto_seal_signature)
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