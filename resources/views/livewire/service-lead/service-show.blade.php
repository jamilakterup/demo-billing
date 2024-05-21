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
            @foreach ($serviceStatusInfos as $serviceStatusInfo)
            <tr>
                <td>{{$serviceStatusInfo['followup']}}</td>
                <td>{{$serviceStatusInfo['consultant']}}</td>
                <td>{{$serviceStatusInfo['status']}}</td>
                <td>{{$serviceStatusInfo['comment']}}</td>
                <td>{{date('d-m-Y',strtotime($serviceStatusInfo['created_at']))}}</td>
                <td>{{date('h:i A',strtotime($serviceStatusInfo['created_at']))}}</td>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</x-livewiremodal-modal>