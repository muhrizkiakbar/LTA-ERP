<div class="modal-dialog modal-md">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Update - Sales</h5>
    </div>
    <div class="modal-body">
      {!! Form::open(['route'=>['backend.user.sales_store'],'method'=>'POST']) !!}
      <div class="row mb-2">
        <label class="col-sm-2 col-form-label fw-bolder">Perusahaan</label>
        <div class="col-sm-6">{!! Form::select('company_id',$company,null,['class'=>'form-control form-control-sm','id'=>'company','placeholder'=>'-- Pilih Perusahaan --']) !!}</div>
      </div>
      <div class="row mb-2">
        <label class="col-sm-2 col-form-label fw-bolder">Sales</label>
        <div class="col-sm-10">{!! Form::select('SalesPersonCode',[],null,['class'=>'form-control form-control-sm select2','id'=>'sales','placeholder'=>'-- Pilih Sales --']) !!}</div>
      </div>
      <input type="hidden" name="users_id" id="users_id" value="{{ $users_id }}">
      <div class="row mb-2">
        <label class="col-sm-2 col-form-label fw-bolder"></label>
        <div class="col-sm-4">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
        </div>
      </div>
      {!! Form::close() !!}
			<table class="table table-bordered table-striped table-xs">
				<thead>
					<tr>
						<th class="text-center">No</th>
						<th class="text-center">Nama Sales</th>
						<th class="text-center">#</th>
					</tr>
				</thead>
				<tbody>
					@php
						$no=1;
					@endphp
					@foreach ($row as $item)
					<tr>
						<td class="text-center">{{ $no++ }}</td>
						<td>{{ $item['SalesPersonName'] }}</td>
						<td class="text-center">
							<a href="{{ route('backend.user.sales_delete',$item['id']) }}">
								<span class="badge badge-danger">DELETE</span>
							</a>
						</td>
					</tr>	
					@endforeach
				</tbody>
			</table>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $(".select2").select2();

    $("#company").on('change', function () {
      var company = $("#company").val();
      var user = $("#users_id").val();
      var url = '{{ route('backend.user.sales_search') }}';
	    $.ajax({
	      type : "POST",
	      url : url,
	      data: { company:company, user:user, _token:"{{ csrf_token() }}"},
	      dataType : "json",
	      success: function(response){ 
	        $("#sales").html(response.listdoc);
	      },
	    });
	  });
  });
</script>