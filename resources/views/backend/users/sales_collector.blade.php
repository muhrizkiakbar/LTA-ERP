<div class="modal-dialog modal-md">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Update - Sales Collector</h5>
    </div>
    <div class="modal-body">
      {!! Form::open(['route'=>['users.sales_collector_store'],'method'=>'POST']) !!}
      <div class="row mb-2">
        <label class="col-sm-2 col-form-label fw-bolder">Sales</label>
        <div class="col-sm-6">{!! Form::select('users_sales_id',$sales,null,['class'=>'form-control','placeholder'=>'-- Pilih Sales --']) !!}</div>
      </div>
      <input type="hidden" name="users_id" value="{{ $users_id }}">
      <div class="row mb-2">
        <label class="col-sm-2 col-form-label fw-bolder"></label>
        <div class="col-sm-4">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
      {!! Form::close() !!}
      <table class="table table-xxs table-striped table-bordered">
        <thead>
          <tr>
            <th class="text-center" width="2%"">No</th>
            <th class="text-center">Nama</th>
            <th class="text-center" width="100px">#</th>
          </tr>
        </thead>
        <tbody>
          @php $no=1; @endphp
          @foreach ($row as $sales)
          <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td>{{ $sales->users_sales_name }}</td>
            <td class="text-center">
              <a href="{{ route('users.sales_collector_delete',$sales->id) }}">
                <span class="badge badge-danger">Delete</span>
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>