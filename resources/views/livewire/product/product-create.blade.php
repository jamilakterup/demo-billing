<!-- Modal -->
<div class="modal fade" wire:ignore.self id="product-create-modal" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$isEdit?'Edit':'Create'}} Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form wire:submit.prevent="{{$isEdit?'productUpdate':'productStore'}}">
        <div class="modal-body">
          <div class="form-group">
            <label for="inputEmail4">Product Name</label>
            <input type="text" wire:model.defer="state.name" class="form-control @error('name') is-invalid @enderror"
              id="" aria-describedby="emailHelp" placeholder="Enter name">
            @error('name')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="inputEmail4">Unit</label>
    
                <select wire:model.defer="state.unit_id" name="unit_id"
                  class="form-control @error('unit_id') is-invalid @enderror">
                  <option value="">--Please Select--</option>
                  @foreach ($unit_array as $key=>$value)
                  <option value="{{$key}}">{{$value}}</option>
                  @endforeach
                </select>
                @error('unit_id')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="inputEmail4">Product Type</label>
    
                <select wire:model.defer="state.product_type_id" name="product_type_id"
                  class="form-control @error('product_type_id') is-invalid @enderror">
                  <option value="">--Please Select--</option>
                  @foreach ($product_type_array as $key=>$value)
                  <option value="{{$key}}">{{$value}}</option>
                  @endforeach
                </select>
                @error('product_type_id')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="product_image">Product Image</label>
                <input type="file" name="image" wire:model.defer="state.image"
                  class="form-control @error('image') is-invalid @enderror" id="product_image" aria-describedby="emailHelp"
                  placeholder="Enter image">
                @error('image')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="inputEmail4">Price</label>
                <input type="text" wire:model.defer="state.price" class="form-control @error('price') is-invalid @enderror"
                  id="" aria-describedby="emailHelp" placeholder="Enter name">
                @error('price')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="product_description">Product Description</label>
            <textarea name="description" wire:model.defer="state.description" id="product_description" cols="30" rows="3" class="form-control @error('description') is-invalid @enderror" id="product_description"></textarea>
            @error('description')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-primary">{{$isEdit?'Update':'Submit'}}</button>
        </div>
      </form>
    </div>
  </div>
</div>