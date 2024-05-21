<table class="table table-responsive">
    <thead>
        <tr>
            {{--  <th>User ID</th>  --}}
            {{--  <th>User name</th>  --}}
            <th>Member name</th>
            <th>Date</th>
            
            <th>Loan ID</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($receivable_amount_users as $receivable_amount_user )
        <tr>
            {{--  <td>{{$receivable_amount_user->user_id}}</td>  --}}
            {{--  <td><img class="img-circle" src="{{asset('img/users/'.$receivable_amount_user->userName->photo)}}" width='25px'/> {{$receivable_amount_user->userName->name}}</td>  --}}
            <td><img class="img-circle" src="{{asset('img/members/'.$receivable_amount_user->memberName->photo)}}" width='25px'/> {{$receivable_amount_user->memberName->name}}</td>
            <td>{{$receivable_amount_user->payment_date}}</td>
            <td>{{$receivable_amount_user->loan_id}}</td>
            <td>{{$receivable_amount_user->amount}}</td>
            <td>
                @if($receivable_amount_user->status==1)
                    
                    <i class="fa fa-check text-success"></i>
                @else
                    <i class="fa fa-times text-danger"></i>
                @endif
            </td>
        </tr>
    @endforeach
        
    </tbody>
</table>