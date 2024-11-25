<div class="row mb-2">
  <label class="col-sm-3 col-form-label fw-bolder">Source</label>
  <div class="col-sm-9">{!! Form::select('company_id',$source,null,['class'=>'form-control','placeholder'=>'-- Pilih Resource API --']) !!}</div>
</div>
<div class="row mb-2">
  <label class="col-sm-3 col-form-label fw-bolder">Function</label>
  <div class="col-sm-9">{!! Form::text('title',null,['class'=>'form-control']) !!}</div>
</div>
<div class="row mb-2">
  <label class="col-sm-3 col-form-label fw-bolder">Deksripsi</label>
  <div class="col-sm-9">{!! Form::text('desc',null,['class'=>'form-control']) !!}</div>
</div>