<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Update Collector Category</h5>
    </div>
    <div class="modal-body">
      {!! Form::open(['route'=>['users.collector_update'],'method'=>'POST']) !!}
      <div class="row mb-2">
        <label class="col-sm-3 col-form-label fw-bolder">Category</label>
        <div class="col-sm-7">
          <select name="collector_id" class="form-control">
            <option value="">-- Pilih Collector Category --</option>
            @foreach ($collector as $coll)
            <option value="{{ $coll->id }}" {!! $collector_id==$coll->id ? 'selected' : '' !!}>{{ $coll->title }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <input type="hidden" name="users_id" value="{{ $users_id }}">
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
