<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Pilih Item...</h5>
    </div>
    <div class="modal-body">
      <table class="table table-xs table-striped table-bordered datatable-basic2">
        <thead>
          <tr>
            <th>#</th>
            <th class="text-center">Kode Item</th>
            <th class="text-center">Deskripsi</th>
            <th class="text-center">Barcode</th>
            <th class="text-center">SKU</th>
            <th class="text-center">NISIB</th>
            <th class="text-center">Satuan Kecil</th>
            <th class="text-center">Stok Available</th>
          </tr>
        </thead>
        <tbody>
          @if (count($row) > 0)
            @php
                $no=1;
            @endphp
            @foreach ($row as $item)
            <tr>
              <td class="text-center">
                <a href="#" class="check_item" data-id="{{ $item['ItemCode'] }}">
                  <span class="badge badge-success">Pilih</span>
                </a>
              </td>
              <td>{{ $item['ItemCode'] }}</td>
              <td>{{ $item['ItemName'] }}</td>
              <td>{{ $item['Barcode'] }}</td>
              <td>{{ $item['SKU'] }}</td>
              <td class="text-center">{{ round($item['SATUAN_BESAR'],2) }}</td>
              <td class="text-center">{{ $item['SATUAN_KECIL'] }}</td>
              <td class="text-center">{{ round($item['inStok'],0) }}</td>
            </tr>
            @endforeach
          @else
          <tr>
            <td class="text-center" colspan="8">
              Maaf, data tidak di temukan !!!
            </td>
          </tr>  
          @endif
        </tbody>
      </table>
      <input type="hidden" name="company" id="company" value="{{ $company }}">
      <input type="hidden" name="whsCode" id="whsCode" value="{{ $whsCode }}">
      <input type="hidden" name="U_CLASS" id="U_CLASS" value="{{ $U_CLASS }}">
			<input type="hidden" name="DocEntry" id="DocEntry" value="{{ $DocEntry }}">
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
    
    $.extend( $.fn.dataTable.defaults, {
			iDisplayLength:10,        
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

        $(".check_item").unbind();
        $(".check_item").click(function(e) {
          var id = $(this).data('id');
          var company = $("#company").val();
          var whsCode = $("#whsCode").val();
          var cardCode = $("#cardCode").val();
          var U_CLASS = $("#U_CLASS").val();
					var DocEntry = $("#DocEntry").val();
          var csrf = '{{ csrf_token() }}';
          var url = '{{ route('backend.app.sales.selectItemUpdate') }}';
          $.ajax({
            url: url,
            type: "POST",
            data : { _token: csrf,id:id,company:company,whsCode:whsCode,cardCode:cardCode,U_CLASS:U_CLASS,DocEntry:DocEntry },
            success: function (response){
              $("#modalEx").modal('hide');
              $("#modalEx2").html(response);
              $("#modalEx2").modal('show',{backdrop: 'true'});
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
