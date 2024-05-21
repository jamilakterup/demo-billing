<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Loan Report</title>
    <style>
        .details td,th {
            border: 1px solid;
            text-align: center;
            padding: 2px;


        }



        body {font-family: 'FreeSerif',sans-serif;}

        .first-title{ font-size: 40px; line-height: 30px; }
        .second-title{font-size: 25px;line-height: 30px; }
        .third-title{font-size: 20px;line-height: 20px;}
        .first-table{ float:left; overflow:hidden; margin-top:20px; margin-bottom:20px;}
        .first-table .one{ padding-right:10%;}
        .first-table td{ margin-bottom:0px;}
        .first-table td.first_p{ float:left; }
        .first-table td.second_p{ float:left;}
        .first-table td.second_p{ }


        .first-table-right {float:left;overflow:hidden; margin-top:20px; margin-bottom:20px;}
        .first-table-right td.first_p{ float:left; margin-left:58%;}
        .first-table-right td.second_p{ float:left;}
        .first-table-right td.second_p{ }



        .second-table{ overflow:hidden; width:100%;}
        .second-table tr{ margin-bottom:10px;}
        .second-table td{ }
        .second-table td.name{ }
        .second-table td.name_feild{ }


        .one-half{  margin-right:0;}
        .one-half.last{margin-right:0px;}
        .one-half p.name{ }
        .one-half p.name_feild{ }
        .one-half.last p.name{ }
        .one-half.last p.name_feild{ }



        .third-table{ margin-top:30px;}
        .third-table h2{ padding:10px 0px; margin:0px; line-height:30px;}
        .third-table table tr, .third-table table td, .third-table table th{ border:1px solid #000; margin:0px;}

        .fourth-table {  border-top:2px solid #000;border-bottom:2px solid #000;}
        .last-table{padding:20px 0px; height:250px; float: left;}
        .last-table.four {}
        .footer1, .footer2, .footer3{border-right:2px solid #000; }
        .footer4{border-right:0px solid #000;}
        .clear{ border-bottom:2px solid #000; width:100%; overflow:hidden; clear:both; margin:10px 0px;}
        .clear2{ border-bottom:2px solid #000; width:100%; overflow:hidden; clear:both; padding:15px 0px 15px; margin-bottom: 20px;}


    </style>
</head>


<head>

<body>

<div class="sr-mrr-table">
    <table style="text-align:center; width:100%;">
        <tr><td class="first-title">দোয়েল সমবায় সমতি</td></tr>
        <tr><td class="third-title">বিমান বন্দর রোড, শালবাগান, সপুরা, রাজশাহী ।</td></tr>
        <tr><td class="third-title">ফোনঃ ৭৬০৮৯৬, মোবাইলঃ ০১৯১১-৪৩১২২৭, ০১৭৩৭-৫৭৫১৫৫</td></tr>
        <tr><td class="third-title">ভ্যাট নিবন্ধন নং- ৬০২১০৪৭৪১৬, কোড নং-৬০১০২</td></tr>
    </table>
    <br>



    <table width="100%" style="font-size: 20px">
        <tr width="100%">
            <td width="75%">Loan Report</td>
            <td width="25%" align="right">Date: {{date('Y-m-d')}}</td>
        </tr>
    </table>

    <table width="100%" class="details" style="border-collapse: collapse">
        <tr style="background-color: #00c054;">
            <td>Member name :
                @if(isset($member_name))
                    {{$member_name}}
                @else
                    Not Define

                @endif
            </td>


            <td>Date :
                @if(isset($from_date) && isset($to_date) )
                    {{$from_date}} to {{$to_date}}
                @else
                    Not Define

                @endif
            </td>

        </tr>
    </table>
    <br>
    <table width="100%" class="details" style="border: 1px solid;border-collapse: collapse">
        <tr>
            <th>SL</th>
            <th>Loan ID</th>
            <th>Member ID</th>
            <th>Start date</th>
            <th>Principal</th>
            <th>Interest</th>
            <th>Paid</th>
            <th>Status</th>
        </tr>
        <?php
        $i=1;
        $total_principal=0;
        $total_interest=0;
        $total_paid=0;

        ?>
        @foreach($loan_reports as $loan_report)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$loan_report->id}}</td>
                <td>{{$loan_report->member_id}}</td>
                <td>{{$loan_report->instalment_start_date}}</td>
                <td>{{$loan_report->principal}}</td>
                <td>{{$loan_report->interest}}</td>
                <td>{{\App\Helper::total_payment($loan_report->id)}}</td>
                <?php
                $total_principal+=$loan_report->principal;
                $total_interest+=$loan_report->interest;
                $total_paid+=\App\Helper::total_payment($loan_report->id);

                ?>
                <td>
                    @if($loan_report->paid==0)
                        <span class="badge badge-warning">Pending</span>
                    @else
                        <span class="badge badge-success">Completed</span>
                    @endif
                </td>
            </tr>

        @endforeach

        <tr>
            <td colspan="4" align="center"><b>Total</b></td>
            <td><b>{{$total_principal}}</b></td>
            <td><b>{{$total_interest}}</b></td>
            <td><b>{{$total_paid}}</b></td>
            <td></td>
        </tr>

    </table>

</div>
</body>
</html>
