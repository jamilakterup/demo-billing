<x-livewiremodal-modal>
    <div class="mb-2">
        @if ($routeName == 'sold_lead')
        <button wire:click='convertToInactive({{$leadCollection}})' class="btn btn-sm btn-danger"><i
                class="fas fa-exchange-alt"></i> Convert To Inactive Lead</button>

        @elseif($routeName == 'inactive_lead')

        <button wire:click='convertToSold({{$leadCollection}})' class="btn btn-sm btn-primary"><i
                class="fas fa-exchange-alt"></i>
            Convert To Sold Lead</button>

        @else

        <button wire:click='convertToSold({{$leadCollection}})' class="btn btn-sm btn-primary"><i
                class="fas fa-exchange-alt"></i>
            Convert To Sold Lead</button>
        <button wire:click='convertToInactive({{$leadCollection}})' class="btn btn-sm btn-danger"><i
                class="fas fa-exchange-alt"></i> Convert To Inactive Lead</button>
        @endif

    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Followup Number</th>
                <th>Consultant</th>
                <th>Status</th>
                <th>Comment</th>
                <th>Followup Date</th>
                <th>Followup Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leadStatusInfos as $leadStatusInfo)
            <tr>
                <td>{{$leadStatusInfo['followup']}}</td>
                <td>{{$leadStatusInfo['consultant']}}</td>
                <td>{{$leadStatusInfo['status']}}</td>
                <td>{{$leadStatusInfo['comment']}}</td>
                <td>{{date('d-m-Y',strtotime($leadStatusInfo['created_at']))}}</td>
                <td>{{date('h:i A',strtotime($leadStatusInfo['created_at']))}}</td>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</x-livewiremodal-modal>