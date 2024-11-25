<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Tambahkan Item</h5>
    </div>
    <div class="modal-body">
      <form method="POST" id="postx">
        <div class="row mb-2">
          <label class="col-sm-3 col-form-label fw-bolder">Item Code</label>
          <div class="col-sm-7">{!! Form::text('ItemCode',$ItemCode,['class'=>'form-control form-control-sm','id'=>'itemCode','readonly'=>true]) !!}</div>
        </div>
        <div class="row mb-2">
          <label class="col-sm-3 col-form-label fw-bolder">Item Name</label>
          <div class="col-sm-7">{!! Form::text('ItemName',$ItemName,['class'=>'form-control form-control-sm','id'=>'itemName','readonly'=>true]) !!}</div>
        </div>
        <div class="row mb-2">
          <label class="col-sm-3 col-form-label fw-bolder">Warehouse</label>
          <div class="col-sm-6">
            {!! Form::text('Warehouse',$whsCode,['class'=>'form-control form-control-sm','id'=>'warehouse','readonly'=>'']) !!}
            <span class="font-13 text-muted">Stok Gudang Utama : {{ rupiahnon($stok) }}</span>
          </div>
        </div>
        <div class="row mb-2">
          <label class="col-sm-3 col-form-label fw-bolder">Satuan</label>
          <div class="col-sm-3">{!! Form::select('Satuan',$satuan,[],['class'=>'form-control form-control-sm','id'=>'Satuan']) !!}</div>
        </div>
        <div class="row mb-2">
          <label class="col-sm-3 col-form-label fw-bolder">Quantity</label>
          <div class="col-sm-2">
            {!! Form::text('Quantity',null,['class'=>'form-control form-control-sm','id'=>'quantity']) !!}
          </div>
        </div>
        <div class="row mb-2">
          <label class="col-sm-3 col-form-label fw-bolder"></label>
          <div class="col-sm-4">
            <input type="hidden" name="cardCode" value="{{ $cardCode }}">
            <input type="hidden" name="company" value="{{ $company }}">
            <input type="hidden" name="stok" value="{{ $stok }}">
            <input type="hidden" name="satuan_kecil" value="{{ $satuan_kecil }}">
            <input type="hidden" name="satuan_besar" value="{{ $satuan_besar }}">
            <input type="hidden" name="harga_jual_pcs" value="{{ $harga_jual_pcs }}">
            <input type="hidden" name="harga_jual_ktn" value="{{ $harga_jual_ktn }}">
            <input type="hidden" name="item_group" value="{{ $item_group }}">
            <input type="hidden" name="nisib" value="{{ $nisib }}">
            <input type="hidden" name="U_CLASS" value="{{ $U_CLASS }}">
						<input type="hidden" name="DocEntry" value="{{ $DocEntry }}">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-primary add">Add Item</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    var swalInit = swal.mixin({
      buttonsStyling: false,
      customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-light',
        denyButton: 'btn btn-light',
        input: 'form-control'
      }
    });

    $(".add").click( function(e) {
      e.preventDefault();
      $.ajax({
        url: "{!! route('backend.app.sales.lines_store') !!}",
        type: "POST",
        data: $("#postx").serialize(),
        dataType: 'JSON',
        success:function(response){
          if (response.message == "sukses") {
            $("#modalEx2").modal('hide');
            loadTable();
            $("#totalBeforeDisc").val(response.totalBefore);
            $("#vatSum").val(response.vatSum);
            $("#total").val(response.total);
          }else if(response.message == "already"){
            swalInit.fire({
              icon: 'error',
              type: 'warning',
              title: 'Oops...',
              text: 'Item code telah di pakai !',
              timer: 3000,
              showCancelButton: false,
              showConfirmButton: false
            });

            $("#modalEx2").modal('hide');
            loadTable();
          } else {
            swalInit.fire({
              icon: 'error',
              type: 'warning',
              title: 'Oops...',
              text: 'Quantity tidak mencukupi !',
              timer: 3000,
              showCancelButton: false,
              showConfirmButton: false
            });

            $("#modalEx2").modal('hide');
            loadTable();
          }
        },
      });
    });
  });
</script>