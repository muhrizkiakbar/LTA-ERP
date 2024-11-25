<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Discount Calculation</h5>
    </div>
		<form method="POST" action="{{ route('backend.app.sales.discount_update') }}">
    <div class="modal-body">
      <table class="table table-xs table-striped table-bordered">
        <thead>
          <tr>
            <th class="text-center">Item No.</th>
            <th class="text-center" width="30%">Item Description</th>
            <th class="text-center">DISC 1</th>
            <th class="text-center">DISC 2</th>
            <th class="text-center">DISC 3</th>
            <th class="text-center">DISC 4</th>
            <th class="text-center">DISC 5</th>
            <th class="text-center">DISC 6</th>
            <th class="text-center">DISC 7</th>
            <th class="text-center">DISC 8</th>
          </tr>
        </thead>
				<tbody>
          @php $no=0; @endphp
          @foreach ($row as $item)
          <tr>
            <td>{{ $item['itemCode'] }}</td>
            <td>{{ $item['itemName'] }}</td>
            <td>{!! Form::text('disc1[]',$item['disc1'],['class'=>'form-control form-control-sm2']) !!}</td>
            <td>{!! Form::text('disc2[]',$item['disc2'],['class'=>'form-control form-control-sm2']) !!}</td>
            <td>{!! Form::text('disc3[]',$item['disc3'],['class'=>'form-control form-control-sm2']) !!}</td>
            <td>{!! Form::text('disc4[]',$item['disc4'],['class'=>'form-control form-control-sm2']) !!}</td>
            <td>{!! Form::text('disc5[]',$item['disc5'],['class'=>'form-control form-control-sm2']) !!}</td>
            <td>{!! Form::text('disc6[]',$item['disc6'],['class'=>'form-control form-control-sm2']) !!}</td>
            <td>{!! Form::text('disc7[]',$item['disc7'],['class'=>'form-control form-control-sm2']) !!}</td>
            <td>{!! Form::text('disc8[]',$item['disc8'],['class'=>'form-control form-control-sm2']) !!}</td>
          </tr>
          <input type="hidden" name="id[]" value="{{ $item['id'] }}">
          <input type="hidden" name="total[]" value="{{ $item['beforeDiscount'] }}">
          <input type="hidden" name="idx[{{ $no }}]" value="{{ $no }}">
          @php $no++;@endphp
          @endforeach
        </tbody>
      </table>
    </div>
		<div class="modal-footer">
      {{ csrf_field() }}
      <input type="hidden" name="docEntry" value="{{ $id }}">
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Update Discount</button>
    </div>
		</form>
  </div>
</div>