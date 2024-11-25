@extends('layouts.backend.app')
@section('content')
<div id="overlay" style="display:none;">
  <div class="spinner-border text-primary m-2" role="status">
    <span class="sr-only">Loading...</span>
  </div>  
</div>
<div class="page-header">
  <div class="page-header-content header-elements-md-inline">
    <div class="d-flex">
      <div class="page-title">
        <h4 class="font-weight-semibold">{{ $title }}</h4>
      </div>
    </div>
  </div>
</div>
<div class="content pt-0">
  <div class="row">
    <div class="col-8">
      @include('backend.template.alert')
      <div class="card">
        <table class="table table-xs table-striped table-bordered datatable-basic">
          <thead>
            <tr>
              <th class="text-center" width="2%"">No</th>
              <th class="text-center">Company</th>
              <th class="text-center">Function</th>
              <th class="text-center">URL</th>
							<th class="text-center">Deskripsi</th>
              <th class="text-center">#</th>
            </tr>
          </thead>
          <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($row as $item)
						<tr>
							<td>{{ $no++ }}</td>
							<td>{{ $item['company'] }}</td>
							<td>{{ $item['function'] }}</td>
							<td>{{ $item['url'] }}</td>
							<td>{{ $item['desc'] }}</td>
							<td class="text-center">
								<a href="{{ route('backend.company_api.delete',$item['id']) }}">
									<span class="badge badge-danger">Delete</span>
								</a>
							</td>
						</tr>	
						@endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-4">
      <div class="card">
        <div class="card-body">
        {!! Form::open(['route'=>['backend.company_api.store'],'method'=>'POST']) !!}
          @include('backend.master.company_api.form')
          <div class="row mb-2">
            <label class="col-sm-3 col-form-label fw-bolder"></label>
            <div class="col-sm-6">
              <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
            </div>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalEx" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
</div>
@endsection
@section('customjs')
<script type="text/javascript">
  $(document).ready(function(){
    $.extend( $.fn.dataTable.defaults, {
			iDisplayLength:25,        
      autoWidth: false,
      stateSave: true,
			columnDefs: [{ 
				orderable: false,
				targets: [  ]
			}],
      dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
      language: {
        search: '<span>Filter:</span> _INPUT_',
        searchPlaceholder: 'Type to filter...',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
      }
    });

    var oTable = $('.datatable-basic').DataTable({
    	"select": "single",
    	"serverSide": false,
    	drawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');

        
      },
      preDrawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
      } 
    });
  });
</script>
@endsection