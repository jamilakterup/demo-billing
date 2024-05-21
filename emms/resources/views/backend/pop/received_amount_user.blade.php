<table class="table table-responsive">
    <thead>
        <tr>
            {{--  <th>User ID</th>  --}}
            <th>User name</th>
            <th>Date</th>
            <th>L.ID</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($received_amount_users as $received_amount_user )
        <tr>
            {{--  <td>{{$receivable_amount_user->user_id}}</td>  --}}
            <td><img class="img-circle" src="{{asset('img/users/'.$received_amount_user->userName->photo)}}" width='25px'/> {{$received_amount_user->userName->name}}</td>
            <td>{{$received_amount_user->payment_date}}</td>
            <td>{{$received_amount_user->loan_id}}</td>
            <td>{{$received_amount_user->payment}}</td>
            
        </tr>
    @endforeach
        
    </tbody>
</table>