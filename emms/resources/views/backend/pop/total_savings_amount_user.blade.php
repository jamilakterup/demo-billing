
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
    @foreach ($savings_amount_users as $savings_amount_user )
        <tr>
            {{--  <td>{{$receivable_amount_user->user_id}}</td>  --}}
            <td><img class="img-circle" src="{{asset('img/users/'.$savings_amount_user->userName->photo)}}" width='25px'/> {{$savings_amount_user->userName->name}}</td>
            <td>{{$savings_amount_user->date}}</td>
            <td>{{$savings_amount_user->type}}</td>
            <td>{{$savings_amount_user->amount}}</td>
            
        </tr>
    @endforeach
        
    </tbody>
</table>