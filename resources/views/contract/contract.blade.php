@extends('default')

@section('styles')	
<style>
	.action-bar{
		margin-bottom: 15px;
	}
</style>
@stop

@section('content')

@if ($contract->Accepted == 1)
<div class="row action-bar">
	<div class="col-md-12">
		<a class="btn btn-warning pdf" href="{{URL::to('contract').'/pdf/'.$contract->SubcontractNbr}}">Print Contract</a>
	</div>
</div>
@else
<div class="row action-bar">
	<div class="col-md-12">
		<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal">Accept Purchase Order</button>
	</div>
</div>
@endif

<div class="row">
	<h3 class="section-color shadow-sm">{{$contract->Project->Description}}</h3>
</div>

<div class="row">
	<div class="col-md-6">
		<table class="table table-striped table-bordered">
			<tr>
				<td class="row-header"><strong>Contract #</strong></td>
				<td class="row-body">{{$contract->SubcontractNbr}}</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Start Date</strong></td>
				<td class="row-body">@date($contract->StartDate)</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Status</strong></td>
				<td class="row-body">{{$contract->Status}}</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Vendor Ref</strong></td>
				<td class="row-body">
					@if (!empty($contract->VendorRef))
					{{$contract->VendorRef}}
					@endif
				</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Contract Total</strong></td>
				<td class="row-body">@currency($contract->SubcontractTotal)</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Description</strong></td>
				<td class="row-body">
					@if (!empty($contract->Description))
					{{$contract->Description}}
					@endif
				</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Installation Manager</strong></td>
				<td class="row-body">
					@if (!empty($contract->Project->InstallationManager))
					{{$contract->Project->InstallationManager}}
					@endif
				</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Notes</strong></td>
				<td class="row-body">{{$contract->note}}</td>
			</tr>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-striped table-bordered">
			<tr>
				<td class="row-header"><strong>Jobsite</strong></td>
				<td class="row-body">{{$contract->Project->Description}}</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Jobsite Address</strong></td>
				<td class="row-body">{{$contract->Project->Addresses->AddressLine1}} <br>
					{{$contract->Project->Addresses->City}}, {{$contract->Project->Addresses->State}} {{$contract->Project->Addresses->PostalCode}}</td>
				</tr>
				<tr>
					<td class="row-header"><strong>GC</strong></td>
					<td class="row-body">{{$contract->Project->GC}}</td>
				</tr>
				<tr>
					<td class="row-header"><strong>Job Requirements</strong></td>
					<td class="row-body">
						<ul>
							@if(!is_array($contract->CCIP) && $contract->CCIP == 1)
							<li>CCIP</li>
							@endif
							@if(!is_array($contract->CertifiedPayroll) && $contract->CertifiedPayroll == 1)
							<li>Certified Payroll</li>
							@endif
							@if(!is_array($contract->DrugTest) && $contract->DrugTest == 1)
							<li>Drug Test</li>
							@endif
							@if(!is_array($contract->IDBadges) && $contract->IDBadges == 1)
							<li>ID Badges</li>
							@endif
							@if(!is_array($contract->OSHA30) && $contract->OSHA30 == 1)
							<li>OSHA 30</li>
							@endif
							@if(!is_array($contract->SafetyMeetingsonJobsite) && $contract->SafetyMeetingsonJobsite == 1)
							<li>Safety Meetings on Jobsite</li>
							@endif
							@if(!is_array($contract->OtherRequirements))
							<li>{{$contract->OtherRequirements}}</li>
							@endif

						</ul>
					</td>
				</tr>
			</table>
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
						<th>Contract Amount</th>
						<th>Billed Amount</th>
						<th>Unbilled Amount</th>
						<th>Retained Amount</th>
						<th>Notes</th>
					</thead>
					@foreach ($contract->SubcontractLines as $detail)
					<tr>
						<td>{{$detail->LineNbr}}</td>
						<td>{{$detail->LineDescription}}</td>
						<td>{{$detail->OrderQty}}</td>
						<td>@currency($detail->ExtCost)</td>
						<td>@currency($detail->BilledAmount)</td>
						<td>@currency($detail->UnbilledAmount)</td>
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
			<h3 class="section-color shadow-sm">Change Orders</h3>
			<hr>
		</div>
	</div>
	<div class="row">
		<div class="col-md">
			<div class="table-responsive">
				<table class="table table-striped table-bordered datatable">
					<thead>
						@if ($contract->Accepted == 1)
						<th></th>
						@endif
						<th>Change Date</th>
						<th>CO #</th>
						<th>Status</th>
						<th>Amount</th>
						<th>CO Description</th>
						<th>Line Description</th>
						<th>Notes</th>
					</thead>
					@if(is_array($contract->ChangeOrders))
					@foreach ($contract->ChangeOrders as $co)
					<tr>
						@if ($contract->Accepted == 1)
						<td><a class="btn btn-info pdf" href="{{URL::to('co').'/pdf/'.$co->ReferenceNbr}}">Print</a></td>
						@endif
						<td>@date($co->ChangeDate)</td>
						<td>{{$co->ReferenceNbr}}</td>
						<td>{{$co->Status}}</td>
						<td>@currency($co->Amount)</td>
						<td>{{$co->DescriptionPMChangeOrder__Description}}</td>
						<td>{{$co->DescriptionDescription}}</td>
						<td>{{$co->note}}</td>
					</tr>
					@endforeach
					@elseif($contract->ChangeOrders != null)
					<tr>
						@if ($contract->Accepted == 1)
						<td><a class="btn btn-info pdf" href="{{URL::to('co').'/pdf/'.$contract->ChangeOrders->ReferenceNbr}}">Print</a></td>
						@endif
						<td>@date($contract->ChangeOrders->ChangeDate)</td>
						<td>{{$contract->ChangeOrders->ReferenceNbr}}</td>
						<td>{{$contract->ChangeOrders->Status}}</td>
						<td>@currency($contract->ChangeOrders->Amount)</td>
						<td>{{$contract->ChangeOrders->DescriptionPMChangeOrder__Description}}</td>
						<td>{{$contract->ChangeOrders->DescriptionDescription}}</td>
						<td>{{$contract->ChangeOrders->note}}</td>
					</tr>
					@endif
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



	@if ($contract->Status == 'Open' && $contract->BillComplete == 0 && $contract->Accepted == 1)
	<div class="row">
		<div class="col-md-12"><button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#invoiceModal">Create Invoice</button></div>
	</div>
	<br>
	@endif
	<div class="row invoices">
		<div class="col-md">
			<div class="table-responsive">
				<table class="table table-striped table-bordered datatable">
					<thead>
						@if ($contract->Accepted == 1)
						<th></th>
						@endif
						<th>Invoice #</th>
						<th>Invoice Date</th>
						<th>Due Date</th>
						<th>Status</th>
						<th>Billed Amount</th>
						<th>Notes</th>
					</thead>
					@if(is_array($contract->Bills))
					@foreach ($contract->Bills as $bill)
					<tr>
						@if ($contract->Accepted == 1)
						<td><a class="btn btn-info pdf inv-line" href="{{URL::to('invoice').'/pdf/'.$bill->ReferenceNbr}}">Print</a></td>
						@endif
						<td>{{$bill->ReferenceNbr}}</td>
						<td>@date($bill->Date)</td>
						<td></td>
						<td>{{$bill->Status}}</td>
						<td>@currency($bill->BilledAmt)</td>
						<td>{{$bill->note}}</td>
					</tr>
					@endforeach
					@elseif($contract->Bills != null)
					<tr>
						@if ($contract->Accepted == 1)
						<td><a class="btn btn-info pdf inv-line" href="{{URL::to('invoice').'/pdf/'.$contract->Bills->ReferenceNbr}}">Print</a></td>
						@endif
						<td>{{$contract->Bills->ReferenceNbr}}</td>
						<td>@date($contract->Bills->Date)</td>
						<td></td>
						<td>{{$contract->Bills->Status}}</td>
						<td>@currency($contract->Bills->BilledAmt)</td>
						<td>{{$contract->Bills->note}}</td>
					</tr>
					@endif
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade modal-lg" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="invoiceModalLabel">Create Invoice</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Contract: {{$contract->SubcontractNbr}}</p>

					<form action="/invoice" method="post" >
						@csrf
						<input type="hidden" name="contractNbr" id="contractNbr" value="{{$contract->SubcontractNbr}}">
						<input type="hidden" name="vendor" id="vendor" value="{{$contract->Vendor}}">
						@if (empty($contract->VendorRef))
						<input type="hidden" name="vendorRef" id="vendorRef" value="">
						@else
						<input type="hidden" name="vendorRef" id="vendorRef" value="{{$contract->VendorRef}}">	
						@endif

						<input type="hidden" name="totalAmount" id="totalAmount">

						<div class="form-group">
							<label>Description</label>
							<input type="text" class="form-control {{ $errors->has('description') ? 'error' : '' }}" name="description" id="description">
							@if ($errors->has('description'))
							<div class="error">
								{{ $errors->first('description') }}
							</div>
							@endif
						</div>
						<div class="form-group">
							<label>Vendor Ref #</label>
							<input type="text" class="form-control {{ $errors->has('vendref') ? 'error' : '' }}" name="vendref" id="vendref">
							@if ($errors->has('vendref'))
							<div class="error">
								{{ $errors->first('vendref') }}
							</div>
							@endif
						</div>
						<br>
						<table class="table table-striped table-bordered">
							<thead>
								<th>Description</th>
								<th>Contract Amount</th>
								<th>Billed Amount</th>
								<th>Unbilled Amount</th>
								<th>Retained Amount</th>
								<th>Percent to Bill</th>
								<th>Amount to Bill</th>
							</thead>
							@foreach ($contract->SubcontractLines as $detail)
							<tr>
								<td>
									{{$detail->LineDescription}}
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][contract]" id="contract{{$detail->LineNbr}}" value="{{$detail->ExtCost}}">
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][billed]"   id="billed{{$detail->LineNbr}}"   value="{{$detail->BilledAmount}}">
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][unbilled]" id="unbilled{{$detail->LineNbr}}" value="{{$detail->UnbilledAmount}}">
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][retained]" id="retained{{$detail->LineNbr}}" value="{{$detail->RetainageAmount}}">
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][line]" id="line{{$detail->LineNbr}}" value="{{$detail->LineNbr}}">
								</td>
								<td>@currency($detail->ExtCost)</td>
								<td>@currency($detail->BilledAmount)</td>
								<td>@currency($detail->UnbilledAmount)</td>
								<td>@currency($detail->RetainageAmount)</td>
								<td>
									<div class="form-group">
										<input type="text" data-type="percent" value="0" line-nbr="{{$detail->LineNbr}}" class="form-control {{ $errors->has('percent'.$detail->LineNbr) ? 'error' : '' }}" name="lines[{{$detail->LineNbr}}][percent]" id="percent{{$detail->LineNbr}}">
										@if ($errors->has('percent'.$detail->LineNbr))
										<div class="error">
											{{ $errors->first('percent'.$detail->LineNbr) }}
										</div>
										@endif
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="test" data-type="amount" value="0"  line-nbr="{{$detail->LineNbr}}" value="" data-type="currency"  class="form-control inv_amount {{ $errors->has('amount'.$detail->LineNbr) ? 'error' : '' }}" name="lines[{{$detail->LineNbr}}][amount]" id="amount{{$detail->LineNbr}}">
										@if ($errors->has('amount'.$detail->LineNbr))
										<div class="error">
											{{ $errors->first('amount'.$detail->LineNbr) }}
										</div>
										@endif
									</div>
								</td>
							</tr>
							@endforeach
						</table>

					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Create</button>
					</form>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-lg" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content alert alert-warning">
				<div class="modal-header alert alert-warning">
					<p>
						By accepting this Purchase Order, I agree to the terms and conditions outlined for the 
						project indicated here, and project specific certifications or testing, as well as those in 
						the SGH Redglaze Subcontractor Agreement.
					</p>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body alert alert-warning">
					<form action="/contract/accept" method="post" >
						@csrf
							<input type="hidden" name="id" value="{{$contract->SubcontractNbr}}">
						<div class="mb-3">
							<label for="acceptedName" class="form-label">Accepted By</label>
							<input type="text" class="form-control" name="acceptedName" id="acceptedName">
						</div>
						<div class="mb-3">
							<label for="acceptedDate" class="form-label">Accepted Date</label>
							<input type="text" class="form-control" name="acceptedDate" id="acceptedDate" value="@date(date(now()))" readonly>
						</div>
						<button class="btn btn-success" type="submit">Accept Purchase Order</button>
					</form>
				</div>
			</div>
		</div>
	</div>




	@stop

	@section('scripts')

	<script>

		$( document ).ready(function() {
			var accepted = {{$contract->Accepted}}

			var acceptModal = new bootstrap.Modal(document.getElementById('acceptModal'))

			// if(accepted == 0){
			// 	acceptModal.show();
			// }

		});

		$("input[data-type='percent']").on('keyup change', function() {
			calcPercent($(this));
			calcTotal();
		});


		$("input[data-type='amount']").on('keyup change', function() {
			calcAmount($(this));
			calcTotal();
		});


		function formatNumber(n) {
			return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
		}


		function formatCurrency(input, blur) {
			var input_val = input.val();

			if (input_val === "") { return; }

			var original_len = input_val.length;

			var caret_pos = input.prop("selectionStart");

			if (input_val.indexOf(".") >= 0) {
				var decimal_pos = input_val.indexOf(".");
				var left_side = input_val.substring(0, decimal_pos);
				var right_side = input_val.substring(decimal_pos);

				left_side = formatNumber(left_side);
				right_side = formatNumber(right_side);

				if (blur === "blur") {
					right_side += "00";
				}

				right_side = right_side.substring(0, 2);

				input_val = "$" + left_side + "." + right_side;

			} else {
				input_val = formatNumber(input_val);
				input_val = "$" + input_val;

				if (blur === "blur") {
					input_val += ".00";
				}
			}

			input.val(input_val);

			var updated_len = input_val.length;
			caret_pos = updated_len - original_len + caret_pos;
			input[0].setSelectionRange(caret_pos, caret_pos);
		}



		function calcPercent(percent) {
			var perc = percent.val();
			var lineNbr = percent.attr('line-nbr');
			var contract = $("#contract"+lineNbr).val();
			var billed = $("#billed"+lineNbr).val();
			var unbilled = $("#unbilled"+lineNbr).val();
			var retained = $("#retained"+lineNbr).val();
			var prevPerc = billed/contract;
			var amount = (perc/100)*contract;
			var billAmt = 0;
			var billPerc = 0;


			if (perc > 99) {
				billAmt = unbilled;
				billPerc = (unbilled/contract)*100;

			}else{
				billAmt = amount;
				billPerc = perc;
			}

			if(billAmt > 0){
				$("#percent"+lineNbr).val(billPerc);
				$("#amount"+lineNbr).val(billAmt);
			}

		}


		function calcAmount(amount) {
			var amt = parseInt(amount.val());
			var lineNbr = amount.attr('line-nbr');
			var contract = parseInt($("#contract"+lineNbr).val());
			var billed = parseInt($("#billed"+lineNbr).val());
			var unbilled = parseInt($("#unbilled"+lineNbr).val());
			var retained = parseInt($("#retained"+lineNbr).val());
			var prevPerc = billed/contract;
			var perc = (amt/contract)*100;
			billAmt = 0;
			billPerc = 0;

			if (amt > unbilled) {
				billAmt = unbilled;
				billPerc = (unbilled/contract)*100;

			}else{
				billAmt = amt;
				billPerc = perc;
			}

			if(billAmt > 0){
				$("#percent"+lineNbr).val(billPerc);
				$("#amount"+lineNbr).val(billAmt);
			}
		}

		$( "#invoiceModal" ).on('shown.bs.modal', function(){
			var count = $('.invoices .inv-line').length+1
			var id = '{{$contract->SubcontractNbr}}';
			$('#vendref').val(id.concat('-').concat(count));
		});

		function calcTotal() {
			var amount = 0;

			$(".inv_amount").each(function(){
				amount += +$(this).val();
			});

			$('#totalAmount').val(+amount);
		}



	</script>
	@stop
