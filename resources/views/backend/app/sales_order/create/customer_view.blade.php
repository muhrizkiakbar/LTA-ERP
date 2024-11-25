<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Pilih Customer...</h5>
    </div>
    <div class="modal-body">
      <table class="table table-xs table-striped table-bordered datatable-basic2">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Kode Customer</th>
            <th class="text-center">Nama Customer</th>
            <th class="text-center">Branch</th>
            <th class="text-center">Alamat</th>
            <th class="text-center">Sub Segment</th>
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
                <a href="#" class="check" data-id="{{ $item['CardCode'] }}">
                  <span class="badge badge-success">Pilih</span>
                </a>
              </td>
              <td>{{ $item['CardCode'] }}</td>
              <td>{{ $item['CardName'] }}</td>
              <td class="text-center">{{ $item['U_CLASS'] }}</td>
              <td>{{ $item['Address'] }}</td>
              <td class="text-center">{{ $item['U_CLEVEL_SEG4'] }}</td>
            </tr>
            @endforeach
          @else
          <tr>
            <td class="text-center" colspan="6">
              Maaf, data tidak di temukan !!!
            </td>
          </tr>  
          @endif
        </tbody>
      </table>
      <input type="hidden" name="company" id="company" value="{{ $company }}">
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
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

        $(".check").unbind();
        $(".check").click(function(e) {
          var id = $(this).data('id');
          var company = $("#company").val();
          var csrf = '{{ csrf_token() }}';
          var url = '{{ route('backend.app.sales.selectCustomerCreate') }}';
          $.ajax({
            url: url,
            type: "POST",
            data : { _token: csrf,id:id,company:company },
            dataType: 'JSON',
            success: function (response){
              $('#cardCode').val(response.CardCode);
              $('#cardName').val(response.CardName);
              $("#Segment").val(response.Segment);
              $("#whsCode").val(response.WhsCode);
              $("#U_CLASS").val(response.uclass);
							$("#Nopol1").val(response.Nopol1);
							$("#Nopol2").val(response.Nopol2);
							$("#BplId").val(response.BPLId);
							$("#SalesPersonCode").val(response.SalesPersonCode);
							$("#salesPersonName").val(response.SalesPersonName);
              $("#modalEx").modal('hide');
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
