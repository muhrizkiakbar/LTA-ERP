@extends('approval.app')
@section('content')
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
	<div class="row">
    <div class="col-12">
			@include('approval.alert')
			<div class="card">
				<div class="card-body">
					<div class="mb-3">
						<h6 class="d-flex font-weight-semibold flex-nowrap mb-0">
							<div class="text-body mr-2">{{ $row['CardCode'] }} <br> {{ $row['CardName'] }}</div>
						</h6>
						<a href="#">{{ $row['SlpName'] }}</a>
						<br><br>
						<h5 class="d-flex font-weight-semibold flex-nowrap">
							<div class="text-body mr-2">{{ $row['Total'] }}</div>
						</h5>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<ul class="media-list">
						@foreach ($row['Lines'] as $item)
						<li class="media">
							<div class="media-body">
								<strong>{{ $item['ItemCode'] }}</strong> <br> 
								{{ $item['ItemName'] }} <br>
								<div class="d-flex flex-wrap">
									<div class="font-weight-semibold">Quantity :</div>
									<div class="ml-auto">{{ $item['Quantity'] }}</div>
								</div>
								<div class="d-flex flex-wrap">
									<div class="font-weight-semibold">Unit Price :</div>
									<div class="ml-auto">{{ rupiah($item['UnitPrice']) }}</div>
								</div>
								<div class="d-flex flex-wrap">
									<div class="font-weight-semibold">Total Price :</div>
									<div class="ml-auto">{{ rupiah($item['LineTotal']) }}</div>
								</div>
								<div class="d-flex flex-wrap">
									<div class="font-weight-semibold">Keterangan :</div>
									<div class="ml-auto">{{ $item['Keterangan'] }}</div>
								</div>
							</div>
						</li>
						<hr>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="d-flex flex-wrap">
				@if ($row['spv_st']==0)
					<a href="{{ route('approval.return.spv_approve',$kd) }}" class="btn btn-success mr-2">Approve</a>
					<a href="{{ route('approval.return.spv_reject',$kd) }}" class="btn btn-danger">Reject</a>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection