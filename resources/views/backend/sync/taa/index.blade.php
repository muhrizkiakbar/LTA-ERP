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
  </div>
</div>
<div class="content pt-0">
  <div class="row mb-2">
    <div class="col-md-12">
      <form method="POST" id="sync">
        <div class="form-group row">
          <label class="col-md-1 col-form-label">Pilih Sales</label>
          <div class="col-md-3">
            {!! Form::select('sales',$sales,null,['class'=>'form-control form-control-sm select2','id'=>'sales','placeholder'=>'-- Pilih Sales --','required'=>true]) !!}
          </div>
          @if ($role==1)
          <div class="col-md-1">
            {!! Form::text('date',null,['class'=>'form-control form-control-sm datepick','id'=>'date','data-language'=>'en','data-date-format'=>'yyyy-mm-dd','required'=>true]) !!}
          </div>
          @else
            <input type="hidden" name="date" id="date" value="{{ date('Y-m-d') }}">
          @endif
          <div class="col-md-2">
            <button class="btn btn-primary btn-sm">SYNC</button>
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
              <th class="text-center">Kode Customer</th>
              <th class="text-center">Customer</th>
              <th class="text-center">Address</th>
              <th class="text-center" width="100px">Date</th>
              <th class="text-center">Branch</th>
              <th class="text-center">Sales</th>
              <th class="text-center" width="150px">Total</th>
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
              <td>{{ $item['CardCode'] }}</td>
              <td>{{ $item['CardName'] }}</td>
              <td>{{ $item['Address'] }}</td>
              <td class="text-center">{{ $item['DocDate'] }}</td>
              <td class="text-center">{{ $item['Branch'] }}</td>
              <td>{{ $item['Sales'] }}</td>
              <td class="text-right">{{ rupiah($item['Total']) }}</td>
              <td class="text-center">
                <a href="javascript:void(0);" data-id="{{ $item['NumAtCard'] }}" class="detail">
                  <span class="badge badge-primary">Detail</span>
                </a>
              </td>
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
    $(".select2").select2();

    $('.datepick').datepicker({
    	autoClose:true
    });

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

        $(".detail").unbind();
        $(".detail").click(function(e) {
          var id = $(this).data('id');
          var url = '{{ route('backend.sync.taa_detail') }}';
          var token = "{{ csrf_token() }}";
          $.ajax({
            url: url,
            type: "POST",
            data : { id:id,_token:token },
            success: function (ajaxData){
              $("#modalEx").html(ajaxData);
              $("#modalEx").modal('show',{backdrop: 'static',keyboard:false});
            }
          });
        });
      },
      preDrawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
      } 
    });

    $("#sync").on("submit",function(e){
      e.preventDefault();
      var date = $("#date").val();
      var sales = $("#sales").val();
      var token = '{{ csrf_token() }}';
      var url = '{{ route('backend.sync.taa_sync') }}';
      $('#overlay').fadeIn();
      $.ajax({
        url : url,
        data  : {sales:sales,date:date,_token:token},
        type : "POST",
        dataType: 'JSON',
        success:function(response){
          if (response.message=="sukses") {
            $('#overlay').hide();
            swalInit.fire({
              title: 'Sync Berhasil!',
              text: 'Anda akan di arahkan dalam 3 Detik',
              icon: 'success',
              timer: 3000,
              showCancelButton: false,
              showConfirmButton: false
            }).then (function() {
              window.location.href = "{!! route('backend.sync.taa') !!}";
            });
          } else {
            $('#overlay').hide();
            swalInit.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Data SFA tidak di temukan',
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