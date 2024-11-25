<div class="row mb-2">
  <label class="col-sm-3 col-form-label fw-bolder">Nama</label>
  <div class="col-sm-9">{!! Form::text('name',null,['class'=>'form-control']) !!}</div>
</div>
<div class="row mb-2">
  <label class="col-sm-3 col-form-label fw-bolder">Username</label>
  <div class="col-sm-9">{!! Form::text('username',null,['class'=>'form-control']) !!}</div>
</div>
<div class="row mb-2">
  <label class="col-sm-3 col-form-label fw-bolder">Password</label>
  <div class="col-sm-9">{!! Form::password('password',['class'=>'form-control']) !!}</div>
</div>
<div class="row mb-2">
  <label class="col-sm-3 col-form-label fw-bolder">Branch</label>
  <div class="col-sm-6">{!! Form::select('branch_sap',$branch,null,['class'=>'form-control','placeholder'=>'-- Pilih Branch --']) !!}</div>
</div>
<div class="row mb-2">
  <label class="col-sm-3 col-form-label fw-bolder">Role</label>
  <div class="col-sm-6">{!! Form::select('users_role_id',$role,null,['class'=>'form-control','placeholder'=>'-- Pilih Role --']) !!}</div>
</div>