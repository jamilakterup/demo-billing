<x-livewiremodal-modal>

    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th>Followup Number</th>
                <th>Consultant</th>
                <th>Status</th>
                <th>Comment</th>
                <th>Followup Date</th>
                <th>Followup Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($soldStatusInfos as $soldStatusInfo)
            <tr>
                <td>{{$soldStatusInfo['followup']}}</td>
                <td>{{$soldStatusInfo['consultant']}}</td>
                <td>{{$soldStatusInfo['status']}}</td>
                <td>{{$soldStatusInfo['comment']}}</td>
                <td>{{date('d-m-Y',strtotime($soldStatusInfo['created_at']))}}</td>
                <td>{{date('h:i A',strtotime($soldStatusInfo['created_at']))}}</td>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</x-livewiremodal-modal>