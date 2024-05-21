
<table class="table table-responsive">
    <thead>
        <tr>
            {{--  <th>User ID</th>  --}}
            <th>User name</th>
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($withdrawal_amount_users as $withdrawal_amount_user)
        <tr>
            {{--  <td>{{$receivable_amount_user->user_id}}</td>  --}}
            <td><img class="img-circle" src="{{asset('img/users/'.$withdrawal_amount_user->userName->photo)}}" width='25px'/> {{$withdrawal_amount_user->userName->name}}</td>
            <td>{{$withdrawal_amount_user->date}}</td>
            <td>{{$withdrawal_amount_user->type}}</td>
            <td>{{$withdrawal_amount_user->amount}}</td>
        </tr>
    @endforeach
        
    </tbody>
</table>