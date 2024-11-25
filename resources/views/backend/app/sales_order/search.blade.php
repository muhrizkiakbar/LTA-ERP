<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Sales Order Document</h5>
    </div>
    <div class="modal-body">
      <table class="table table-xs table-striped table-bordered datatable-basic2">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Document Number</th>
            <th class="text-center">Nama Customer</th>
						<th class="text-center">Alamat</th>
            <th class="text-center">Date</th>
            <th class="text-center">Due Date</th>
						<th class="text-center">Sales</th>
            <th class="text-center">Total</th>
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
                <a href="#" class="check" data-id="{{ $item['DocNum'] }}">
                  <span class="badge badge-success">Pilih</span>
                </a>
              </td>
              <td>{{ $item['DocNum'] }}</td>
              <td>{{ $item['CardName'] }}</td>
							<td>{{ $item['Alamat'] }}</td>
              <td class="text-center">{{ $item['DocDate'] }}</td>
							<td class="text-center">{{ $item['DocDueDate'] }}</td>
              <td>{{ $item['SlpName'] }}</td>
              <td class="text-right">{{ rupiah($item['Netto']) }}</td>
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
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
		$(".check").click(function(e) {
			var id = $(this).data('id');
			var company = $("#company").val();
			var csrf = '{{ csrf_token() }}';
			var url = '{{ route('backend.app.sales.selectDocument') }}';
			$.ajax({
				url: url,
				data : { _token: csrf,id:id,company:company },
				type : "POST",
        dataType : "JSON",
        success: function (response){
          if (response.message=="sukses") {
            var base = "{{ url('/backend/app/sales/detail/') }}";
            var href = base+"/"+response.docnum;
            window.location.href = href;
          }
        }
			});
		});
	});
</script>