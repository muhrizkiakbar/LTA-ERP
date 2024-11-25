<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Update Collector Category</h5>
    </div>
    <div class="modal-body">
      {!! Form::model($row,['route'=>['users.update',$row->id],'method'=>'PUT','files' => true]) !!}
      @include('sap.users.form')
      <div class="row mb-2">
        <label class="col-sm-3 col-form-label fw-bolder"></label>
        <div class="col-sm-4">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
