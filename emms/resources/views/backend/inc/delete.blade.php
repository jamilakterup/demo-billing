<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="mymodal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="" style="padding: 1rem;
                    border-bottom: 1px solid
                    #e9ecef;
                    border-top-left-radius: .3rem;
                    border-top-right-radius: .3rem;">
                <h5 class="text-center d-block text-danger">Are you sure you want to delete this?</h5>
            </div>
            <div class="modal-body">

                <div class="d-flex justify-content-center">
                    <div>
                        {{Form::open(['route'=>['savings.destroy','test'],'method'=>'delete','class'=>'form form-inline'])}}
                        {{Form::hidden('saving_id',null,['class'=>'btn btn-danger btn-xl','id'=>'delete_id'])}}
                        {{Form::submit('Yes',['class'=>'btn btn-danger btn-xl'])}}
                        {{Form::close()}}
                    </div>
                    <button type="button" class="btn btn-default ml-2" data-dismiss="modal">No</button>


                </div>

            </div>
            <div class="modal-footer">
            </div>

        </div>
    </div>
</div>