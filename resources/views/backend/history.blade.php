@extends('layouts.backend.app')
@section('content')
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
    <div class="col-md-12">
      <div class="card">
        <table class="table table-xs table-striped table-bordered datatable-basic2">
          <thead>
            <tr>
							<th class="text-center" width="5%"">No</th>
							<th class="text-center">Date & Time</th>
							<th class="text-center">User</th>
							<th class="text-center">Action</th>
							<th class="text-center">Desc</th>
						</tr>
          </thead>
          <tbody>
            @php $no=1; @endphp
						@foreach ($row as $item)
						<tr>
							<td class="text-center">{{ $no++ }}</td>
							<td class="text-center">{{ $item['time'] }}</td>
							<td>{{ $item['user'] }}</td>
							<td class="text-center">{!! $item['action'] !!}</td>
							<td>{!! $item['desc'] !!}</td>
						</tr>
						@endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="modalEx" class="modal fade" tabindex="-1">
</div>
@endsection
@section('customjs')
<script type="text/javascript">
  $(document).ready(function() {

    $.extend( $.fn.dataTable.defaults, {
			iDisplayLength:25,        
      autoWidth: false,
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

    var oTable = $('.datatable-basic2').DataTable({
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