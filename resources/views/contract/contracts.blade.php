@extends('default')

@section('styles')	
<style>

</style>
@stop

@section('content')

<div class="row">
	<h3 class="section-color shadow-sm">Open Contracts</h3>
</div>

<div class="row">
	<div class="col-md">
		<div class="table-responsive">
			<table class="table table-striped table-bordered datatable">
				<thead class="table-light">
					<th>Order #</th>
					<th class="d-none d-lg-table-cell">Start Date</th>
					<th class="d-none d-lg-table-cell">Status</th>
					<th class="d-none d-lg-table-cell">Vendor Ref</th>
					<th>Contract Total</th>
					<th>Unbilled Total</th>
					<th>Description</th>
					<th class="d-none d-lg-table-cell">Installation Manager</th>
				</thead>

				@foreach($open_contracts as $o_contract)
				<tr>
					<td><a href="{{URL::to('contract').'/'.$o_contract->SubcontractNbr}}" target="_blank">{{$o_contract->SubcontractNbr}}</a></td>
					<td class="d-none d-lg-table-cell">@date($o_contract->StartDate)</td>
					<td class="d-none d-lg-table-cell">{{$o_contract->Status}}</td>
					<td class="d-none d-lg-table-cell">
						@if (!empty($o_contract->VendorRef))
						{{$o_contract->VendorRef}}
						@endif
					</td>
					<td>@currency($o_contract->SubcontractTotal)</td>
					<td>@currency($o_contract->UnbilledLineTotal)</td>
					<td>
					@if (!empty($o_contract->Description))
						{{$o_contract->Description}}
						@endif
					</td>
					<td class="d-none d-lg-table-cell">{{$o_contract->PM}}</td>
				</tr>
				@endforeach

			</table>
		</div>
	</div>
</div>

<div class="row">
	<h3 class="section-color shadow-sm">Archived Contracts</h3>
</div>
<div class="row">
	<div class="col-md">
		<div class="table-responsive">
			<table class="table table-striped table-bordered datatable">
				<thead class="table-light">
					<th>Order #</th>
					<th class="d-none d-lg-table-cell">Start Date</th>
					<th class="d-none d-lg-table-cell">Status</th>
					<th class="d-none d-lg-table-cell">Vendor Ref</th>
					<th>Contract Total</th>
					<th>Unbilled Total</th>
					<th>Description</th>
					<th class="d-none d-lg-table-cell">Installation Manager</th>
				</thead>

				@foreach($archived_contracts as $contract)
				<tr>
					<td><a href="{{URL::to('contract').'/'.$contract->SubcontractNbr}}" target="_blank">{{$contract->SubcontractNbr}}</a></td>
					<td class="d-none d-lg-table-cell">@date($contract->StartDate)</td>
					<td class="d-none d-lg-table-cell">{{$contract->Status}}</td>
					<td class="d-none d-lg-table-cell">
						@if (!empty($contract->VendorRef))
						{{$contract->VendorRef}}
						@endif
					</td>
					<td>@currency($contract->SubcontractTotal)</td>
					<td>@currency($contract->UnbilledLineTotal)</td>
					<td>
										@if (!empty($contract->Description))
						{{$contract->Description}}
						@endif
					</td>
					<td class="d-none d-lg-table-cell">{{$contract->PM}}</td>
				</tr>
				@endforeach

			</table>
		</div>
	</div>
</div>

@stop
