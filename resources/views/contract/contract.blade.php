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
	<div class="col-md-6">
		<div class="table-responsive">
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
					<td class="row-body">{{$contract->Description}}</td>
				</tr>
				<tr>
					<td class="row-header"><strong>Project Manager</strong></td>
					<td class="row-body">{{$contract->PM}}</td>
				</tr>
				<tr>
					<td class="row-header"><strong>Notes</strong></td>
					<td class="row-body">{{$contract->note}}</td>
				</tr>
			</table>
		</div>
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
					<td class="row-header"><strong></strong></td>
					<td class="row-body"></td>
				</tr>
				<tr>
					<td class="row-header"><strong></strong></td>
					<td class="row-body"></td>
				</tr>
				<tr>
					<td class="row-header"><strong></strong></td>
					<td class="row-body"></td>
				</tr>
				<tr>
					<td class="row-header"><strong></strong></td>
					<td class="row-body"></td>
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
			<h3 class="section-color shadow-sm">Invoices</h3>
			<hr>
		</div>
	</div>
	@if ($contract->Status == 'Open')
	<div class="row">
		<div class="col-md-12"><button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#invoiceModal">Create Invoice</button></div>
	</div>
	<br>
	@endif
	<div class="row">
		<div class="col-md">
			<div class="table-responsive">
				<table class="table table-striped table-bordered datatable">
					<thead>
						<th>Date</th>
						<th>Ref Number</th>
						<th>Status</th>
						<th>Billed Amount</th>
						<th>Notes</th>
					</thead>
					@if(is_array($contract->Bills))
					@foreach ($contract->Bills as $bill)
					<tr>
						<td>@date($bill->Date)</td>
						<td>{{$bill->ReferenceNbr}}</td>
						<td>{{$bill->Status}}</td>
						<td>@currency($bill->BilledAmt)</td>
						<td>{{$bill->note}}</td>
					</tr>
					@endforeach
					@elseif($contract->Bills != null)
					<tr>
						<td>@date($contract->Bills->Date)</td>
						<td>{{$contract->Bills->ReferenceNbr}}</td>
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
						

						<div class="form-group">
							<label>Description</label>
							<input type="text" class="form-control {{ $errors->has('description') ? 'error' : '' }}" name="description" id="description">
							@if ($errors->has('description'))
							<div class="error">
								{{ $errors->first('description') }}
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
										<input type="test" data-type="amount" value="0"  line-nbr="{{$detail->LineNbr}}" value="" data-type="currency"  class="form-control {{ $errors->has('amount'.$detail->LineNbr) ? 'error' : '' }}" name="lines[{{$detail->LineNbr}}][amount]" id="amount{{$detail->LineNbr}}">
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

	@stop

	@section('scripts')

	<script>
		// $("input[data-type='currency']").on({
		// 	keyup: function() {
		// 		formatCurrency($(this));
		// 	},
		// 	blur: function() { 
		// 		formatCurrency($(this), "blur");
		// 	}
		// });

		$("input[data-type='percent']").on('keyup change', function() {
			calcPercent($(this));
		});


		$("input[data-type='amount']").on('keyup change', function() {
			calcAmount($(this));
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


	</script>
	@stop
