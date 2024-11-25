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
          <label class="col-md-1 col-form-label">Cari Dokumen</label>
          <div class="col-md-2">
            {!! Form::select('company',$company,null,['class'=>'form-control form-control-sm','id'=>'company','placeholder'=>'-- Pilih Perusahaan --','required'=>true]) !!}
          </div>
          <div class="col-md-3">
            {!! Form::select('sales',[],null,['class'=>'form-control form-control-sm select2','id'=>'sales','placeholder'=>'-- Pilih Sales --','required'=>true]) !!}
          </div>
          <div class="col-md-2">
            {!! Form::text('DocNum',null,['class'=>'form-control form-control-sm','id'=>'DocNum']) !!}
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary btn-sm cari">Cari</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row mb-4">
            @include('backend.app.sales_order.form.form_top')
          </div>
          <div class="row mb-2">
            <label class="col-md-1 col-form-label font-weight-semibold">Cari Item</label>
            <div class="col-md-1">
              {!! Form::text('whsCode',null,['class'=>'form-control form-control-sm','id'=>'whsCode','readonly'=>'']) !!}
              <input type="hidden" name="U_CLASS" id="U_CLASS">
            </div>
            <div class="col-md-2">
              {!! Form::text('itemName',null,['class'=>'form-control form-control-sm','id'=>'itemName']) !!}
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-md-12">
              <div id="loadTable"></div>
            </div>
          </div>
					<div class="row mb-3">
            @include('backend.app.sales_order.form.form_bottom')
          </div>
					<div class="row ">
						<div class="col-md-7">
							<div class="row mb-2">
								<label class="col-sm-3 col-form-label font-weight-semibold"></label>
								<div class="col-sm-6">
									<a href="javascript:void(0);" class="btn btn-md btn-primary save">Simpan</a>
								</div>
							</div>
						</div>
					</div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalEx" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
</div>
<div class="modal fade" id="modalEx2" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
</div>
@endsection
@section('customjs')
<script type="text/javascript">
  $(document).ready(function() {
    $(".select2").select2();

    $('.datepick').datepicker({
    	autoClose:true
    });

    loadTable();

    var swalInit = swal.mixin({
      buttonsStyling: false,
      customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-light',
        denyButton: 'btn btn-light',
        input: 'form-control'
      }
    });

    $("#company").on('change', function () {
      var company = $("#company").val();
      var url = '{{ route('backend.searchSalesByCompany') }}';
	    $.ajax({
	      type : "POST",
	      url : url,
	      data: { company:company, _token:"{{ csrf_token() }}"},
	      dataType : "json",
	      success: function(response){ 
	        $("#sales").html(response.listdoc);
	      },
	    });
	  });

    $("#cardName").on('keypress', function (e) {
      var keycode = (e.keyCode ? e.keyCode : e.which);
      if(keycode == '13'){
        var company = $("#company").val();
        var cardName = $("#cardName").val();
        var csrf = "{{ csrf_token() }}";
        var url = '{{ route('backend.app.sales.searchCustomerCreate') }}';
        $.ajax({
          url : url,
          data  : {company:company,cardName:cardName,_token:csrf},
          type : "POST",
          success:function(response){
            if (company=='') {
              swalInit.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Maaf, perusahaan belum di pilih',
                timer: 1500,
                showCancelButton: false,
                showConfirmButton: false
              });
            } else {
              $("#modalEx").html(response);
              $("#modalEx").modal('show',{backdrop: 'true'});
            }
          }
        });
      }
    });

    $("#itemName").on('keypress', function (e) {
      var keycode = (e.keyCode ? e.keyCode : e.which);
      if(keycode == '13'){
        var company = $("#company").val();
        var whsCode = $("#whsCode").val();
        var itemName = $("#itemName").val();
        var U_CLASS = $("#U_CLASS").val();
        var csrf = "{{ csrf_token() }}";
        var url = '{{ route('backend.app.sales.searchItemCreate') }}';
        $.ajax({
          url : url,
          data  : {company:company,itemName:itemName,whsCode:whsCode,U_CLASS:U_CLASS,_token:csrf},
          type : "POST",
          success:function(response){
            if (company=='') {
              swalInit.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Maaf, perusahaan belum di pilih',
                timer: 1500,
                showCancelButton: false,
                showConfirmButton: false
              });
            } else if (whsCode=='') {
              swalInit.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Maaf, gudang belum di pilih',
                timer: 1500,
                showCancelButton: false,
                showConfirmButton: false
              });
            } else {
              $("#modalEx").html(response);
              $("#modalEx").modal('show',{backdrop: 'true'});
            }
          }
        });
      }
    });

		$(".save").click(function(e){
			e.preventDefault();
      $('#overlay').show();
			var cardCode = $("#cardCode").val();
      var docDate = $("#docDate").val();
      var docDueDate = $("#docDueDate").val();
      var numAtCard = $("#numAtCard").val();
      var SalesPersonCode = $("#SalesPersonCode").val();
      var Comments = $("#remarks").val();
      var BplId = $("#BplId").val();
      var Nopol1 = $("#Nopol1").val();
      var Nopol2 = $("#Nopol2").val();
			var company = $("#company").val();
      var csrf = "{!! csrf_token() !!}";
      var url = '{{ route('backend.app.sales.manual') }}';
			$.ajax({
        url : url,
        data  : {
          cardCode:cardCode,
          docDate:docDate,
          docDueDate:docDueDate,
          numAtCard:numAtCard,
          SalesPersonCode:SalesPersonCode,
          Comments:Comments,
          BplId:BplId,
          Nopol1:Nopol1,
          Nopol2:Nopol2,
					company:company,
          _token:csrf},
        type : "POST",
        dataType : "JSON",
        success: function (response){
          if (response.message=="sukses") {
            var base = "{{ url('backend/cuti/tahunan/detail/') }}";
            var href = base+"/"+response.docnum;
            $('#overlay').hide();
            Swal.fire({
              icon: 'success',
              type: 'success',
              title: 'Push dokumen berhasil !',
              text: 'Anda akan di arahkan dalam 3 Detik',
              timer: 1500,
              showCancelButton: false,
              showConfirmButton: false
            }).then (function() {
              window.location.href = href
            });
          } else {
            $('#overlay').hide();
            Swal.fire({
              icon: 'error',
              type: 'warning',
              title: 'Oops...',
              text: 'Error, harap cek history !',
              timer: 3000,
              showCancelButton: false,
              showConfirmButton: false
            });
          }
        }
      });
		});

		$(".cari").click(function(e){
			e.preventDefault();
      // $('#overlay').show();
			var company = $("#company").val();
			var sales = $("#sales").val();
			var DocNum = $("#DocNum").val();
      var csrf = "{!! csrf_token() !!}";
      var url = '{{ route('backend.app.sales.search') }}';
			$.ajax({
        url : url,
        data  : {
					company:company,
					sales:sales,
					DocNum:DocNum,
          _token:csrf},
        type : "POST",
        success: function (response){
          $("#modalEx").html(response);
          $("#modalEx").modal('show',{backdrop: 'true'});
        }
      });
		});
  });

  function loadTable(){
    var url = '{{ route('backend.app.sales.temp_table') }}';
    $.ajax({
      url: url,
      type: "GET",
      success : function(data){
        $('#loadTable').html(data);
      }
    });
  }

</script>
@endsection