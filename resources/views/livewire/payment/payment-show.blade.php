<!-- Modal -->
<div class="modal fade" wire:ignore.self id="show-payment-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ isset($state['invoice']['customer']['name']) ?
                    $state['invoice']['customer']['name'] : ''
                    }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if (isset($showhistory[0]) && isset($showhistory[0]['invoice']) &&
            isset($showhistory[0]['invoice']['number']))
            <h3 class="text-center">Invoice Number: {{ $showhistory[0]['invoice']['number'] }}</h3>
            @endif

            @if (isset($showhistory[0]) && isset($showhistory[0]['invoice']) &&
            isset($showhistory[0]['invoice']['customer']) && isset($showhistory[0]['invoice']['customer']['name']))
            <h4 class="text-center">Customer: {{ $showhistory[0]['invoice']['customer']['name'] }}</h4>
            @endif

            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th>Payment Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($showhistory as $item)
                    <tr class="text-center">
                        <td>{{ $item['amount'] }} TK</td>
                        <td>{{ $item['payment_type']['name'] }}</td>
                        <td>{{ $item['date'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
</div>