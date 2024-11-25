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
              <th class="text-center">Nama</th>
              <th class="text-center">Username</th>
              <th class="text-center">Branch</th>
              <th class="text-center">Role</th>
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
              <td>{{ $item['nama'] }}</td>
              <td>{{ $item['username'] }}</td>
              <td>{{ $item['branch'] }}</td>
              <td>{{ $item['role'] }}</td>
              <td class="text-center">
                <div class="dropdown position-static">
                  <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                    <i class="icon-menu9"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" style="">
                    @if ($item['role_id']==3)
                    <a href="javascript:void(0)" class="dropdown-item sales" data-id="{{ $item['id'] }}">
                      <i class="icon-users"></i> Sales
                    </a> 
                    <a href="javascript:void(0)" class="dropdown-item sap_lta" data-id="{{ $item['id'] }}">
                      <i class="icon-lock"></i> Akun SAP LTA
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item sap_taa" data-id="{{ $item['id'] }}">
                      <i class="icon-lock"></i> Akun SAP TAA
                    </a> 
                    @elseif ($item['role_id']==4)
                    <a href="javascript:void(0)" class="dropdown-item collector" data-id="{{ $item['id'] }}">
                      <i class="icon-users"></i> Collector
                    </a>
                    @elseif ($item['role_id']==6)
                    <a href="javascript:void(0)" class="dropdown-item sales_collector" data-id="{{ $item['id'] }}">
                      <i class="icon-users"></i> Sales Collector
                    </a>
                    @endif
                    <a href="javacscript:void(0);" class="dropdown-item edit" data-id="{{ $item['id'] }}">
                      <i class="icon-pencil7"></i> Edit
                    </a>
                    @if (auth()->user()->users_role_id==1)
                    <a href="" class="dropdown-item">
                      <i class="icon-bin"></i> Delete
                    </a>
                    @endif
                  </div>
                </div>
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
        {!! Form::open(['route'=>['backend.user.store'],'method'=>'POST']) !!}
          @include('backend.users.form')
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

        $(".sales").unbind();
        $(".sales").click(function(e) {
          var id = $(this).data('id');
          var url = '{{ route('backend.user.sales') }}';
          var token = "{{ csrf_token() }}";
          $.ajax({
            url: url,
            type: "POST",
            data : { id:id,_token:token },
            success: function (ajaxData){
              $("#modalEx").html(ajaxData);
              $("#modalEx").modal('show',{backdrop: 'true'});
            }
          });
        });

        $(".collector").unbind();
        $(".collector").click(function(e) {
          var id = $(this).data('id');
          var url = '{{ route('backend.user.collector') }}';
          var token = "{{ csrf_token() }}";
          $.ajax({
            url: url,
            type: "POST",
            data : { id:id,_token:token },
            success: function (ajaxData){
              $("#modalEx").html(ajaxData);
              $("#modalEx").modal('show',{backdrop: 'true'});
            }
          });
        });

        $(".sales_collector").unbind();
        $(".sales_collector").click(function(e) {
          var id = $(this).data('id');
          var url = '{{ route('backend.user.sales_collector') }}';
          var token = "{{ csrf_token() }}";
          $.ajax({
            url: url,
            type: "POST",
            data : { id:id,_token:token },
            success: function (ajaxData){
              $("#modalEx").html(ajaxData);
              $("#modalEx").modal('show',{backdrop: 'true'});
            }
          });
        });

        $(".edit").unbind();
        $(".edit").click(function(e) {
          var id = $(this).data('id');
          var url = '{{ route('backend.user.edit') }}';
          var token = "{{ csrf_token() }}";
          $.ajax({
            url: url,
            type: "POST",
            data : { id:id,_token:token },
            success: function (ajaxData){
              $("#modalEx").html(ajaxData);
              $("#modalEx").modal('show',{backdrop: 'true'});
            }
          });
        });

        $(".sap_lta").unbind();
        $(".sap_lta").click(function(e) {
          var id = $(this).data('id');
          var url = '{{ route('backend.user.sap_lta') }}';
          var token = "{{ csrf_token() }}";
          $.ajax({
            url: url,
            type: "POST",
            data : { id:id,_token:token },
            success: function (ajaxData){
              $("#modalEx").html(ajaxData);
              $("#modalEx").modal('show',{backdrop: 'true'});
            }
          });
        });

        $(".sap_taa").unbind();
        $(".sap_taa").click(function(e) {
          var id = $(this).data('id');
          var url = '{{ route('backend.user.sap_taa') }}';
          var token = "{{ csrf_token() }}";
          $.ajax({
            url: url,
            type: "POST",
            data : { id:id,_token:token },
            success: function (ajaxData){
              $("#modalEx").html(ajaxData);
              $("#modalEx").modal('show',{backdrop: 'true'});
            }
          });
        });
      },
      preDrawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
      } 
    });
  });
</script>
@endsection