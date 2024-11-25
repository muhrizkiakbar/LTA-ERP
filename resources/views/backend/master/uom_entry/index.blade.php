@extends('layouts.backend.app')
@section('content')
<div id="overlay" style="display:none;">
  <div class="spinner-border text-primary" role="status"></div>
  <br/>
  Loading...
</div>
<!-- Page header -->
<div class="page-header">
  <div class="page-header-content header-elements-md-inline">
    <div class="d-flex">
      <div class="page-title">
        <h4 class="font-weight-semibold">{{ $title }}</h4>
      </div>
    </div>
    {{-- <div class="header-elements d-none py-0 mb-3 mb-md-0">
      <a href="javascript:void(0)" class="btn btn-primary sync">
        <i class="icon-reset mr-2"></i> Sync From SAP
      </a>
    </div> --}}
  </div>
</div>
<div class="content pt-2">
  <div class="row mb-2">
    <div class="col-md-12">
      <form method="POST" id="sync">
        <div class="form-group row">
          <label class="col-md-2 col-form-label">Pilih Perusahaan</label>
          <div class="col-md-3">
            {!! Form::select('company_id',$company,null,['class'=>'form-control form-control-sm select2','id'=>'company_id','placeholder'=>'-- Pilih Perusahaan --','required'=>true]) !!}
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary btn-sm">
              <i class="icon-reset mr-2"></i> Sync From SAP
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <table class="table table-xs table-striped table-bordered datatable-basic2">
          <thead>
            <tr>
              <th class="text-center" width="2%">No</th>
              <th class="text-center">Perusahaan</th>
              <th class="text-center">Uom Entry</th>
              <th class="text-center">Uom Code</th>
            </tr>
          </thead>
          <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($row as $item)
            <tr>
              <td class="text-center">{{ $no++ }}</td>
              <td class="text-center">{{ $item['company'] }}</td>
              <td class="text-center">{{ $item['UomEntry'] }}</td>
              <td class="text-center">{{ $item['UomCode'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
@section('customjs')
<script type="text/javascript">
  $(document).ready(function() {
    $(".select2").select2();

    var swalInit = swal.mixin({
      buttonsStyling: false,
      customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-light',
        denyButton: 'btn btn-light',
        input: 'form-control'
      }
    });

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

    $("#sync").on("submit",function(e){
      e.preventDefault();
      var company_id = $("#company_id").val();
      var token = '{{ csrf_token() }}';
      var url = '{{ route('backend.master.uom_entry_sync') }}';
      $('#overlay').fadeIn();
      $.ajax({
        url : url,
        data  : {company_id:company_id,_token:token},
        type : "POST",
        dataType: 'JSON',
        success:function(response){
          if (response.message=="sukses") {
            $('#overlay').hide();
            swalInit.fire({
              icon: 'success',
              type: 'success',
              title: 'Sync Berhasil!',
              text: 'Anda akan di arahkan dalam 3 Detik',
              timer: 1500,
              showCancelButton: false,
              showConfirmButton: false
            }).then (function() {
              window.location.href = "{!! route('backend.master.uom_entry') !!}";
            });
          } else {
            $('#overlay').hide();
            swalInit.fire({
              icon: 'error',
              type: 'warning',
              title: 'Oops...',
              text: 'Data tidak di temukan',
              timer: 1500,
              showCancelButton: false,
              showConfirmButton: false
            });
          }
        },
      });
    });
  });
</script>
@endsection