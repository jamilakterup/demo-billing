<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .text-center {
            text-align: center;
        }

        .base {
            font-size: 12px;
        }

        .medium {
            font-size: 16px;
            line-height: 38px;
        }

        .bold {
            font-weight: bold
        }

        .flex {
            display: flex;
        }

        .yellow {
            color: #c5543b;
        }
    </style>
</head>

<body>
    <section>
        <div class="text-center">
            <img style="width:245px;" src="{{asset('logo/rajit.png')}}" alt="">
            <p class="text-center base" style="margin-bottom:-10px">H#122, Gonokpara, Ghoramara, Boalia,
                Rajshahi-6100 Bangladesh,Phone
                : +8801762623193,
                URL:<a href="www.rajit.net">www.rajit.net</a></p>
            <hr>
        </div>

        <div>
            <p class="base" style="text-align:left; margin-bottom:-20px">SN:
                <strong><em>{{$payment['invoice']['number']}}</em></strong>
            </p>
            <p class="base" style="text-align:right; margin-top:-50px">Date: {{ date('d-m-y',
                strtotime($payment['date'])) }}</p>
        </div>

        <div>
            <h2 style="text-decoration:underline" class="text-center yellow">Money Receipt</h2>
        </div>

        <p class="medium">Received with thanks from <span
                class="bold">{{$payment['invoice']['customer']['company_name']}}</span> TK. {{$payment['amount']}}/-
            <?php
                    $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                    echo '<span class="bold" style="text-transform: capitalize;">'.'('.$digit->format($payment['amount']).' Taka) Only.</span>';
                    ?>
            by Cash/ChequeNo__________________________ Bank_________________________
            Branch______________________Dated
            {{ date('d-m-y', strtotime($payment['date'])) }} on account of <span
                class="bold">{{$payment['invoice']['subject']}}</span>

        </p>


        {{--
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
        </table> --}}
    </section>

</body>

</html>