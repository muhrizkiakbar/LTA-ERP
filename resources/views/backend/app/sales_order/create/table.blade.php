<table class="table table-xs table-striped table-bordered">
  <thead>
    <tr>
      <th class="text-center">#</th>
      <th class="text-center">Item No.</th>
      <th class="text-center">Item Description</th>
      <th class="text-center">Quantity</th>
      <th class="text-center">Satuan</th>
      <th class="text-center">Unit Price</th>
      <th class="text-center">Tax Code</th>
      <th class="text-center">Gudang</th>
      <th class="text-center">Distr. Rule</th>
      <th class="text-center">Total (LC)</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($row as $item)
    <tr>
      <td class="text-center">
        <a href="javascript:void(0);" class="delete" data-id="{{ $item['id'] }}">
          <i class="icon-trash text-danger"></i>
        </a>
      </td>
      <td>{{ $item['itemCode'] }}</td>
      <td>{{ $item['itemDesc'] }}</td>
      <td class="text-right">{{ $item['qty'] }}</td>
      <td>{{ $item['unitMsr'] }}</td>
      <td class="text-right">{{ rupiah($item['unitPrice']) }}</td>
      <td>{{ $item['taxCode'] }}</td>
      <td>{{ $item['whsCode'] }}</td>
      <td>{{ $item['cogs'] }}</td>
      <td class="text-right">{{ rupiah($item['docTotal']) }}</td>
    </tr>  
    @endforeach
  </tbody>
</table>
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

    $(".delete").click(function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      var url = '{{ route('backend.app.sales.temp_delete') }}';
      var token = "{{ csrf_token() }}";
      swalInit.fire({
        title: 'Anda yakin delete Item ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        buttonsStyling: false,
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        }
      }).then(function(result) {
        if(result.value) {
          $('#overlay').fadeIn();
          $.ajax({
            url : url,
            data  : {id:id,_token:token},
            type : "POST",
            dataType: 'JSON',
            success:function(response){
              if (response.message=="sukses") {
                $('#overlay').hide();
                swalInit.fire({
                  title: 'Delete Berhasil!',
                  text: 'Anda akan di arahkan dalam 3 Detik',
                  icon: 'success',
                  timer: 3000,
                  showCancelButton: false,
                  showConfirmButton: false
                }).then (function() {
                  loadTable();
									$("#totalBeforeDisc").val(response.totalBefore);
									$("#vatSum").val(response.vatSum);
									$("#total").val(response.total);
                });
              } else {
                $('#overlay').hide();
                swalInit.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Close PO gagal !',
                  timer: 1500,
                  showCancelButton: false,
                  showConfirmButton: false
                });
              }
            },
          });
        }
        else if(result.dismiss === swal.DismissReason.cancel) {
          swalInit.fire(
            'Cancelled',
            'Your imaginary file is safe :)',
            'error'
          );
        }
      });
    });
  });
</script>