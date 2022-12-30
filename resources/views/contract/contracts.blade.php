@extends('default')

@section('styles')	
<style>

</style>
@stop

@section('content')

<div class="row">
	<h3 class="section-color shadow-sm">Contracts</h3>
</div>

<div class="row">
	<div class="col-md">
		<div class="table-responsive">
			<table class="table table-striped table-bordered datatable">
				<thead class="table-light">
					<th>Order #</th>
					<th>Start Date</th>
					<th>Status</th>
					<th>Vendor Ref</th>
					<th>Contract Total</th>
					<th>Unbilled Total</th>
					<th>Description</th>
					<th>Project Manager</th>
				</thead>

				@foreach($contracts as $contract)
				<tr>
					<td><a href="{{URL::to('contract').'/'.$contract->SubcontractNbr}}" target="_blank">{{$contract->SubcontractNbr}}</a></td>
					<td>@date($contract->StartDate)</td>
					<td>{{$contract->Status}}</td>
					<td>
						@if (!empty($contract->VendorRef))
						{{$contract->VendorRef}}
						@endif
					</td>
					<td>@currency($contract->SubcontractTotal)</td>
					<td>@currency($contract->UnbilledLineTotal)</td>
					<td>{{$contract->Description}}</td>
					<td>{{$contract->PM}}</td>
				</tr>
				@endforeach

			</table>
		</div>
	</div>
</div>

@stop
