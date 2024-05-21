<!-- Modal -->
<div class="modal fade" wire:ignore.self id="show-vat-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Experience Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <iframe src="{{ isset($state['certificate_file']) ?
            asset('/storage/'.$state['certificate_file']): ''
            }}" style="overflow: auto; width: 100%; min-height: 500px;" frameborder="0"></iframe>


        </div>
    </div>
</div>