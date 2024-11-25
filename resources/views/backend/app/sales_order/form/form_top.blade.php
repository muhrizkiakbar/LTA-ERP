<div class="col-md-6">
  <div class="row mb-2">
    <label class="col-sm-4 col-form-label font-weight-semibold">1. Customer</label>
    <div class="col-sm-7">{!! Form::text('CardCode',isset($cardCode) ? $cardCode : NULL,['class'=>'form-control form-control-sm','id'=>'cardCode','readonly'=>'']) !!}</div>
  </div>
  <div class="row mb-2">
    <label class="col-sm-4 col-form-label">Name</label>
    <div class="col-sm-7">{!! Form::text('CardName',isset($cardName) ? $cardName : NULL,['class'=>'form-control form-control-sm','id'=>'cardName','readonly'=> isset($cardName) ? '' : false]) !!}</div>
  </div>
  <div class="row mb-2">
    <label class="col-sm-4 col-form-label font-weight-semibold">5. Customer Ref No</label>
    <div class="col-sm-7">{!! Form::text('NumAtCard',isset($numAtCard) ? $numAtCard : NULL,['class'=>'form-control form-control-sm','id'=>'numAtCard','readonly'=> isset($numAtCard) ? '' : false]) !!}</div>
  </div>
  <div class="row mb-2">
    <div class="col-sm-4">{!! Form::select('local_currency',$local_currency,null,['class'=>'form-control form-control-sm']) !!}</div>
    <div class="col-sm-3">{!! Form::text('segment',isset($segment) ? $segment : NULL,['class'=>'form-control form-control-sm','id'=>'Segment','readonly'=>'']) !!}</div>
  </div>
</div>
<div class="col-md-6">
	@isset($DocNum)
	<div class="row mb-2">
    <label class="col-sm-3 col-form-label font-weight-semibold">Document Number</label>
    <div class="col-sm-6">{!! Form::text('DocNum',$DocNum,['class'=>'form-control form-control-sm','id'=>'docNum','readonly'=>true]) !!}</div>
  </div>	
	@endisset
  <div class="row mb-2">
    <label class="col-sm-3 col-form-label font-weight-semibold">2. Posting Date</label>
    <div class="col-sm-6">
      @if (isset($closing))
        @if ($date==$closing)
        {!! Form::text('DocDate',$date,['class'=>'form-control form-control-sm datepick','data-language'=>'en','data-date-format'=>'yyyy-mm-dd','id'=>'docDate']) !!}
        @else
        {!! Form::text('DocDate',$date,['class'=>'form-control form-control-sm','id'=>'docDate','readonly'=>true]) !!}
        @endif
      @endif
    </div>
  </div>
  <div class="row mb-2">
    <label class="col-sm-3 col-form-label font-weight-semibold">3. Due Date</label>
    <div class="col-sm-6">{!! Form::text('DocDueDate',$dueDate,['class'=>'form-control form-control-sm','id'=>'docDueDate','readonly'=>true]) !!}</div>
  </div>
  <div class="row">
    <label class="col-sm-3 col-form-label font-weight-semibold">4. Document Date</label>
    <div class="col-sm-6">{!! Form::text('document_date',$date,['class'=>'form-control form-control-sm','readonly'=>true]) !!}</div>
  </div>
</div>