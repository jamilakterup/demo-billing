<x-livewiremodal-modal>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Source</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Followup No.</th>
                    <th>Next Followup</th>
                    <th style="width:128px">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($leadCollections->where('followup_status', 'true'))
                @foreach ($leadCollections->where('followup_status', 'true') as $leadCollection)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$leadCollection['name']}}</td>
                    <td>{{$leadCollection['email']}}</td>
                    <td>{{$leadCollection['phone']}}</td>
                    <td>{{$leadCollection['source']}}</td>
                    <td>
                        {{
                        optional($leadCollection->lead_status()->latest()->first())->comment
                        ?
                        Str::limit($leadCollection->lead_status()->latest()->first()->comment,
                        35, '...') : '' }}

                    </td>
                    <td>
                        {{$leadCollection->lead_status()->latest() ->first()->status??""}}
                    </td>
                    <td>
                        {{$leadCollection->lead_status()->latest()->first()->followup??""}}
                    </td>
                    <td>
                        {{ optional($leadCollection->lead_status()->latest()->first())->date
                        ?
                        \Carbon\Carbon::parse($leadCollection->lead_status()->latest()->first()->date)->format('d-m-Y')
                        : "" }}

                    </td>
                    <td>
                        <a class="btn btn-outline-primary btn-sm fas fa-eye"
                            onclick='_openModal("Show Lead Details", "lead-collection.collection-show",{{json_encode(["leadCollection"=>$leadCollection->id])}},"lg")'></a>

                        <a class="btn btn-outline-primary btn-sm"
                            onclick='_openModal("Edit Lead", "lead-collection.collection-edit",{{json_encode(["leadCollection"=>$leadCollection->id])}},"lg")'><i
                                class="fas fa-edit"></i></a>

                        <a class="btn btn-outline-danger btn-sm"
                            wire:click.prevent="deleteLead({{$leadCollection->id}})"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</x-livewiremodal-modal>