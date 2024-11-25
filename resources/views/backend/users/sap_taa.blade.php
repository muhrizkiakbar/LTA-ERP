<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Update Akun SAP TAA</h5>
    </div>
    <div class="modal-body">
      {!! Form::model($row,['route'=>['users.update',$row->id],'method'=>'PUT','files' => true]) !!}
      <div class="row mb-2">
        <label class="col-sm-3 col-form-label fw-bolder">Username</label>
        <div class="col-sm-9">{!! Form::text('username_sap_taa',null,['class'=>'form-control']) !!}</div>
      </div>
      <div class="row mb-2">
        <label class="col-sm-3 col-form-label fw-bolder">Password</label>
        <div class="col-sm-9">{!! Form::text('password_sap_taa',['class'=>'form-control']) !!}</div>
      </div>
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
