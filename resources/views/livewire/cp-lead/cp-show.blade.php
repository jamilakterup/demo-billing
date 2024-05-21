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
            @foreach ($cpStatusInfos as $cpStatusInfo)
            <tr>
                <td>{{$cpStatusInfo['followup']}}</td>
                <td>{{$cpStatusInfo['consultant']}}</td>
                <td>{{$cpStatusInfo['status']}}</td>
                <td>{{$cpStatusInfo['comment']}}</td>
                <td>{{date('d-m-Y',strtotime($cpStatusInfo['created_at']))}}</td>
                <td>{{date('h:i A',strtotime($cpStatusInfo['created_at']))}}</td>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</x-livewiremodal-modal>