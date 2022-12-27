@extends('default')

@section('styles')	
<style>

</style>
@stop

@section('content')

<div class="row">
	<h3 class="section-color shadow-sm">{{$contract->Project->Description}}</h3>
</div>

<div class="row">
	<div class="col-md">
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<tr>
					<td class="row-header"><strong>Contract #</strong></td>
					<td class="row-body">{{$contract->SubcontractNbr}}</td>

					<td class="row-header"><strong>Jobsite</strong></td>
					<td class="row-body">{{$contract->Project->Description}}</td>
				</tr>
				<tr>
					<td class="row-header"><strong>Start Date</strong></td>
					<td class="row-body">@date($contract->StartDate)</td>

					<td class="row-header"><strong>Jobsite Address</strong></td>
					<td class="row-body">{{$contract->Project->Addresses->AddressLine1}} <br>
						{{$contract->Project->Addresses->City}}, {{$contract->Project->Addresses->State}} {{$contract->Project->Addresses->PostalCode}}</td>
					</tr>
					<tr>
						<td class="row-header"><strong>Status</strong></td>
						<td class="row-body">{{$contract->Status}}</td>

						<td class="row-header"><strong>GC</strong></td>
						<td class="row-body">{{$contract->Project->GC}}</td>
					</tr>
					<tr>
						<td class="row-header"><strong>Contract Total</strong></td>
						<td class="row-body">@currency($contract->SubcontractTotal)</td>

						<td class="row-header"><strong></strong></td>
						<td class="row-body"></td>
					</tr>
					<tr>
						<td class="row-header"><strong>Description</strong></td>
						<td class="row-body">{{$contract->Description}}</td>

						<td class="row-header"><strong></strong></td>
						<td class="row-body"></td>
					</tr>
					<tr>
						<td class="row-header"><strong>Project Manager</strong></td>
						<td class="row-body">{{$contract->PM}}</td>

						<td class="row-header"><strong></strong></td>
						<td class="row-body"></td>
					</tr>
					<tr>
						<td class="row-header"><strong>Notes</strong></td>
						<td class="row-body">{{$contract->note}}</td>

						<td class="row-header"><strong></strong></td>
						<td class="row-body"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md">
			<h3 class="section-color shadow-sm">Details</h3>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md">
			<div class="table-responsive">
				<table class="table table-striped table-bordered datatable">
					<thead>
						<th>Line #</th>
						<th>Description</th>
						<th>Qty</th>
						<th>Unit Cost</th>
						<th>Ext Cost</th>
						<th>Retained Amount</th>
						<th>Notes</th>
					</thead>
					@foreach ($contract->SubcontractLines as $detail)
					<tr>
						<td>{{$detail->LineNbr}}</td>
						<td>{{$detail->LineDescription}}</td>
						<td>{{$detail->OrderQty}}</td>
						<td>@currency($detail->UnitCost)</td>
						<td>@currency($detail->ExtCost)</td>
						<td>@currency($detail->RetainageAmount)</td>
						<td>{{$detail->note}}</td>
					</tr>
					@endforeach
				</table>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md">
			<h3 class="section-color shadow-sm">Invoices</h3>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md">
			<div class="table-responsive">
				<table class="table table-striped table-bordered datatable">
					<thead>
						<th>Date</th>
						<th>Ref Number</th>
						<th>Status</th>
						<th>Vendor Ref</th>
						<th>Billed Amount</th>
						<th>Amount</th>
						<th>Notes</th>
					</thead>
					@if(is_array($contract->Billing))
					@foreach ($contract->Billing as $bill)
					<tr>
						<td>@date($bill->Date)</td>
						<td>{{$bill->ReferenceNbr}}</td>
						<td>{{$bill->Status}}</td>
						<td>{{$bill->VendorRef}}</td>
						<td>@currency($bill->BilledAmt)</td>
						<td>@currency($bill->Amount)</td>
						<td>{{$bill->note}}</td>
					</tr>
					@endforeach
					@elseif($contract->Billing != null)
					<tr>
						<td>@date($contract->Billing->Date)</td>
						<td>{{$contract->Billing->ReferenceNbr}}</td>
						<td>{{$contract->Billing->Status}}</td>
						<td>{{$contract->Billing->VendorRef}}</td>
						<td>@currency($contract->Billing->BilledAmt)</td>
						<td>@currency($contract->Billing->Amount)</td>
						<td>{{$contract->Billing->note}}</td>
					</tr>
					@endif
				</table>
			</div>
		</div>
	</div>

	@stop
