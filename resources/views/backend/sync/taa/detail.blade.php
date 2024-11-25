<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">{{ $title }}</h5>
    </div>
    <div class="modal-body">
      <table class="table table-xs table-striped table-bordered">
        <thead>
          <tr>
            <th class="text-center" width="2%">No</th>
            <th class="text-center">Item Code</th>
            <th class="text-center">Item Name</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Measurement</th>
            <th class="text-center" width="120px">Unit Price</th>
            <th class="text-center" width="140px">Total</th>
          </tr>
        </thead>
        <tbody>
          @if (empty($lines['row']))
          <tr>
            <td colspan="7" class="text-center">
              <strong>Maaf, Data kosong !!!</strong>
            </td>
          </tr>
          @else
            @php
                $no=1;
            @endphp
            @foreach ($lines['row'] as $item)
            <tr>
              <td class="text-center">{{ $no++ }}</td>
              <td>{{ $item['ItemCode'] }}</td>
              <td>{{ $item['ItemName'] }}</td>
              <td class="text-right">{{ $item['Qty'] }}</td>
              <td class="text-center">{{ $item['Satuan'] }}</td>
              <td class="text-right">{{ rupiah($item['UnitPrice']) }}</td>
              <td class="text-right">{{ rupiah($item['Total']) }}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="6" class="text-center">
                <strong>Grand Total</strong>
              </td>
              <td class="text-right">
                <strong>{{ rupiah($lines['total']) }}</strong>
              </td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <a href="javascript:void(0);" data-id="{{ $id }}" class="btn btn-primary btn-sm push">Push To SO</a>
      <a href="javascript:void(0);" data-id="{{ $id }}" class="btn btn-danger btn-sm closex">Close PO</a>
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

    $(".push").click(function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      var url = '{{ route('backend.sync.taa_push') }}';
      var token = "{{ csrf_token() }}";
      $("#modalEx").modal('hide');
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

    $(".closex").click(function(e) {
      e.preventDefault();
      var id = $(this).data('id');
      var url = '{{ route('backend.sync.taa_close') }}';
      var token = "{{ csrf_token() }}";
      $("#modalEx").modal('hide');
      swalInit.fire({
        title: 'Anda yakin close PO ?',
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
                  title: 'Close Berhasil!',
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