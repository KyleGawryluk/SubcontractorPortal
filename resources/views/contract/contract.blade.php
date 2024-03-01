@extends('default')

@section('title')
{{$contract->Project->Description}}
@stop

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
		<span class="btn btn-primary attachments" data-bs-toggle="modal" data-bs-target="#attachmentsModal">Attachments ({{count($contract->files)}})</span>
		<span class="btn btn-info" data-bs-toggle="modal" data-bs-target="#newFileModal"><i class="bi bi-cloud-upload"></i></span>
	</div>
</div>
@else
<div class="row action-bar">
	<div class="col-md-12">
		<span class="btn btn-primary attachments" data-bs-toggle="modal" data-bs-target="#attachmentsModal">Attachments ({{count($contract->files)}})</span>
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
				<td class="row-header"><strong>Original Contract Total</strong></td>
				<td class="row-body">@currency($contract->OriginalContractAmt)</td>
			</tr>

			<tr>
				<td class="row-header"><strong>ChangeOrderTotal</strong></td>
				<td class="row-body">@currency($contract->ChangeOrderTotal)</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Revised Contract Total</strong></td>
				<td class="row-body">@currency($contract->SubcontractTotal)</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Unbilled Total</strong></td>
				<td class="row-body">@currency($contract->UnbilledLineTotal)</td>
			</tr>
			<tr>
				<td class="row-header"><strong>Description</strong></td>
				<td class="row-body">
					@if (!empty($contract->ProjectDescription))
					{{$contract->ProjectDescription}}
					@endif
				</td>
			</tr>


		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-striped table-bordered">
			<tr>
				<td class="row-header"><strong>Installation Manager</strong></td>
				<td class="row-body">
					@if (!empty($contract->Project->InstallationManager))
					{{$contract->Project->InstallationManager}}
					@endif
				</td>
			</tr>
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
				<tr>
					<td class="row-header"><strong>Notes</strong></td>
					<td class="row-body">{{$contract->note}}</td>
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
				<table class="table table-striped table-bordered datatable-details">
					<thead>
						<th>Line #</th>
						<th>Description</th>
						<th>Original Contract Amount</th>
						<th>CO Amount</th>
						<th>Revised Contract Amount</th>
						<th>Billed Amount</th>
						<th>Unbilled Amount</th>
						<th>Retained Amount</th>
						<th>Notes</th>
					</thead>
					@foreach ($contract->SubcontractLines as $detail)
					<tr>
						<td>{{$detail->LineNbr}}</td>
						<td>{{$detail->LineDescription}}</td>
						<td>@currency($detail->UnitCost)</td>
						<td>@currency($detail->ChangeAmt)</td>
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
		</div>
	</div>
	@if ($contract->Status == 'Open' && $contract->BillComplete == 0 && $contract->Accepted == 1)
	<div class="row">
		<div class="col-md-12"><button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#coModal">Change Order Request</button></div>
	</div>
	<br>
	@endif
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
						<th>Subcontract Line #</th>
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
						<td>{{$co->POLineNbr}}</td>
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


	<br>
	<div class="row">
		<div class="col-md">
			<h3 class="section-color shadow-sm">Invoices</h3>
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
				<table class="table table-striped table-bordered datatable-bills">
					<thead>
						@if ($contract->Accepted == 1)
						<th></th>
						@endif
						<th>Invoice #</th>
						<th>Invoice Date</th>
						<th>Due Date</th>
						{{-- <th>Description</th> --}}
						<th>Status</th>
						<th>Billed Amount</th>
						<th>Notes</th>
					</thead>
					@if(is_array($contract->Bills))
					@foreach ($contract->Bills as $bill)
					<tr>
						@if ($contract->Accepted == 1)
						<td>
							<div class="btn-toolbar mb-3" role="toolbar">
								<div class="btn-group" role="group">
									<a class="btn btn-warning pdf inv-line loading" href="{{URL::to('invoice').'/pdf/'.$bill->ReferenceNbr}}">Print</a>

									<span class="btn btn-primary attachments" data-bs-toggle="modal" data-bs-target="#bill{{$bill->ReferenceNbr}}AttachmentsModal"><i class="bi bi-paperclip"></i> ({{count($bill->files)}})</span>

									<span class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bill{{$bill->ReferenceNbr}}FileModal"><i class="bi bi-cloud-upload"></i></span>
								</div>
							</div>
						</td>
						@endif
						<td>{{$bill->ReferenceNbr}}</td>
						<td>@date($bill->Date)</td>
						<td></td>
						{{-- <td>{{$bill->Description}}</td> --}}
						<td>{{$bill->Status}}</td>
						<td>@currency($bill->Amount)</td>
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
						{{-- <td>{{$bill->Description}}</td> --}}
						<td>{{$contract->Bills->Status}}</td>
						<td>@currency($contract->Bills->Amount)</td>
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
							<input type="text" class="form-control {{ $errors->has('description') ? 'error' : '' }}" name="description" id="description" value="{{$contract->Project->Description}}">
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
						<table class="table table-striped table-bordered ">
							<thead>
								<th>Description</th>
								<th class="d-none d-lg-table-cell">Contract Amount</th>
								<th class="d-none d-lg-table-cell">Billed Amount</th>
								<th>Unbilled Amount</th>
								<th class="d-none d-lg-table-cell">Retained Amount</th>
								<th class="d-none d-lg-table-cell">Percent to Bill</th>
								<th>Amount to Bill</th>
							</thead>
							@foreach ($contract->SubcontractLines as $detail)
							@if ($detail->Closed != 1)
							<tr>
								<td>
									{{$detail->LineDescription}}
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][contract]" id="contract{{$detail->LineNbr}}" value="{{$detail->ExtCost}}">
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][billed]"   id="billed{{$detail->LineNbr}}"   value="{{$detail->BilledAmount}}">
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][unbilled]" id="unbilled{{$detail->LineNbr}}" value="{{$detail->UnbilledAmount}}">
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][retained]" id="retained{{$detail->LineNbr}}" value="{{$detail->RetainageAmount}}">
									<input type="hidden" line-nbr="{{$detail->LineNbr}}" name="lines[{{$detail->LineNbr}}][line]" id="line{{$detail->LineNbr}}" value="{{$detail->LineNbr}}">
								</td>
								<td class="d-none d-lg-table-cell">@currency($detail->ExtCost)</td>
								<td class="d-none d-lg-table-cell">@currency($detail->BilledAmount)</td>
								<td>@currency($detail->UnbilledAmount)</td>
								<td class="d-none d-lg-table-cell">@currency($detail->RetainageAmount)</td>
								<td class="d-none d-lg-table-cell">
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
										<input type="test" data-type="amount" value="0"  line-nbr="{{$detail->LineNbr}}" value="" data-type="currency"  class="currency form-control inv_amount {{ $errors->has('amount'.$detail->LineNbr) ? 'error' : '' }}" name="lines[{{$detail->LineNbr}}][amount]" id="amount{{$detail->LineNbr}}">
										@if ($errors->has('amount'.$detail->LineNbr))
										<div class="error">
											{{ $errors->first('amount'.$detail->LineNbr) }}
										</div>
										@endif
									</div>
								</td>
							</tr>
							@endif
							@endforeach
						</table>

					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary loading">Create</button>
					</form>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>



	<div class="modal fade modal-lg" id="coModal" tabindex="-1" aria-labelledby="coModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="coModalLabel">Request Change Order</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Contract: {{$contract->SubcontractNbr}}</p>
					<form action="/co" method="post" >
						@csrf
						<input type="hidden" name="contractNbr" id="contractNbr" value="{{$contract->SubcontractNbr}}">
						<input type="hidden" name="project" id="project" value="{{$contract->Project->ProjectID}}">

						<div class="form-group">
							<label>Project</label>
							<input type="text" class="form-control {{ $errors->has('project') ? 'error' : '' }}" name="project" id="project" value="{{$contract->Project->Description}}" disabled>
						</div>

						<div class="form-group">
							<label>Subject</label>
							<input type="text" class="form-control {{ $errors->has('description') ? 'error' : '' }}" name="description" id="description">
							@if ($errors->has('description'))
							<div class="error">
								{{ $errors->first('description') }}
							</div>
							@endif
						</div>

						<div class="form-group">
							<label>Subcontract Line</label>
							<table class="table table-striped table-bordered">
								<thead>
									<th>Include</th>
									<th>Line #</th>
									<th>Description</th>
									<th>Revised Contract Amount</th>
									<th>Unbilled Amount</th>
									<th>Change Amount</th>
								</thead>
								@foreach ($contract->SubcontractLines as $detail)
								<tr>
									<td>
										<div class="form-check">
											<input class="form-check-input woLineItem" type="checkbox" name="woLine[{{$detail->id}}][check]" id="woLineCheck[]" value="{{$detail->id}}">
											<input type="hidden" name="woLine[{{$detail->id}}][projectTask]" id="projectTask{{$detail->id}}" value="{{$detail->ProjectTask}}">
											<input type="hidden" name="woLine[{{$detail->id}}][costCode]" id="costCode{{$detail->id}}" value="{{$detail->CostCode}}">
											<input type="hidden" name="woLine[{{$detail->id}}][invId]" id="invId{{$detail->id}}" value="{{$detail->InventoryID}}">
											<input type="hidden" name="woLine[{{$detail->id}}][lineNbr]" id="lineNbr{{$detail->id}}" value="{{$detail->LineNbr}}">
											<input type="hidden" name="woLine[{{$detail->id}}][project]" id="project{{$detail->id}}" value="{{$detail->Project}}">
										</div>
									</td>
									<td>{{$detail->LineNbr}}</td>
									<td>
										
										<div class="form-group">
											<input type="text" class="form-control {{ $errors->has('description') ? 'error' : '' }}" name="woLine[{{$detail->id}}][desc]" id="desc{{$detail->id}}" disabled>
											@if ($errors->has('woLine['.$detail->id.'][desc]'))
											<div class="error">
												{{ $errors->first('woLine['.$detail->id.'][desc]') }}
											</div>
											@endif
										</div>
									</td>
									<td>@currency($detail->ExtCost)</td>
									<td>@currency($detail->UnbilledAmount)</td>
									<td><input type="text" class="currency form-control {{ $errors->has('lineAmount') ? 'error' : '' }}" name="woLine[{{$detail->id}}][lineAmount]" id="lineAmount{{$detail->id}}" disabled></td>
								</tr>
								@endforeach
							</table>
						</div>


						<div class="form-group">
							<label>Detailed Description</label>
							<textarea  type="text" class="form-control {{ $errors->has('description') ? 'error' : '' }}" name="detailedDescription" id="detailedDescription"></textarea>
							@if ($errors->has('detailedDescription'))
							<div class="error">
								{{ $errors->first('detailedDescription') }}
							</div>
							@endif
						</div>
						<br>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary loading">Create</button>
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
						<input type="hidden" name="nbr" value="{{$contract->SubcontractNbr}}">
						<input type="hidden" name="id" value="{{$contract->id}}">
						<div class="mb-3">
							<label for="acceptedName" class="form-label">Accepted By</label>
							<input type="text" class="form-control" name="acceptedName" id="acceptedName">
						</div>
						<div class="mb-3">
							<label for="acceptedDate" class="form-label">Accepted Date</label>
							<input type="text" class="form-control" name="acceptedDate" id="acceptedDate" value="@date(date(now()))" readonly>
						</div>
						<button class="btn btn-success loading" type="submit">Accept Purchase Order</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade modal-lg" id="attachmentsModal" tabindex="-1" aria-labelledby="attachmentsModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="invoiceModalLabel">Attachments</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table class="table">
						@if (!isset($contract->files))
						<p>No Attachments Found</p>
						@else
						<table class="table table-striped">
							<thead>
								<th>Filename</th>
								<th>Download</th>
							</thead>
							@foreach ($contract->files as $file)
							<tr>
								<td>{{substr($file->filename, strrpos($file->filename, '\\') + 1)}}</td>
								<td><a class="" href="file/{{$file->id}}/{{substr($file->filename, strrpos($file->filename, '\\') + 1)}}" target="_blank"><i class="bi bi-download"></i></a></td>
							</tr>
							@endforeach
						</table>
						@endif
					</table>
				</div>
			</div>
		</div>
	</div>

	@foreach ($contract->Bills as $bill)
	<div class="modal fade modal-lg" id="bill{{$bill->ReferenceNbr}}AttachmentsModal" tabindex="-1" aria-labelledby="bill{{$bill->ReferenceNbr}}AttachmentsModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="bill{{$bill->ReferenceNbr}}AttachmentsModalLabel">Invoice Attachments</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table class="table">
						@if (!isset($bill->files))
						<p>No Attachments Found</p>
						@else
						<table class="table table-striped">
							<thead>
								<th>Filename</th>
								<th>Download</th>
							</thead>
							@foreach ($bill->files as $file)
							<tr>
								<td>{{substr($file->filename, strrpos($file->filename, '\\') + 1)}}</td>
								<td><a class="" href="file/{{$file->id}}/{{substr($file->filename, strrpos($file->filename, '\\') + 1)}}" target="_blank"><i class="bi bi-download"></i></a></td>
							</tr>
							@endforeach
						</table>
						@endif
					</table>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade modal-lg" id="bill{{$bill->ReferenceNbr}}FileModal" tabindex="-1" aria-labelledby="bill{{$bill->ReferenceNbr}}FileModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="bill{{$bill->ReferenceNbr}}FileModalLabel">Attach file to Invoice #{{$bill->ReferenceNbr}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="alert alert-warning">
						Accepted file types: .jpg .pdf
					</p>
					<form action="/bill/file" method="post" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="nbr" value="{{$bill->ReferenceNbr}}">
						<input type="hidden" name="id" value="{{$bill->id}}">
						<input type="file" class="form-control" name="attachment" id="attachment">
						<hr>
						<button class="btn btn-success loading" type="submit">Upload File</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	@endforeach



	@foreach ($contract->ChangeOrders as $co)
	<div class="modal fade modal-lg" id="wo{{$co->ReferenceNbr}}AttachmentsModal" tabindex="-1" aria-labelledby="wo{{$co->ReferenceNbr}}AttachmentsModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="wo{{$co->ReferenceNbr}}AttachmentsModalLabel">Work Order Attachments</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table class="table">
						@if (!isset($co->files))
						<p>No Attachments Found</p>
						@else
						<table class="table table-striped">
							<thead>
								<th>Filename</th>
								<th>Download</th>
							</thead>
							@foreach ($co->files as $file)
							<tr>
								<td>{{substr($file->filename, strrpos($file->filename, '\\') + 1)}}</td>
								<td><a class="" href="file/{{$file->id}}/{{substr($file->filename, strrpos($file->filename, '\\') + 1)}}" target="_blank"><i class="bi bi-download"></i></a></td>
							</tr>
							@endforeach
						</table>
						@endif
					</table>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade modal-lg" id="wo{{$co->ReferenceNbr}}FileModal" tabindex="-1" aria-labelledby="wo{{$co->ReferenceNbr}}FileModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="wo{{$co->ReferenceNbr}}FileModalLabel">Attach file to Work Ordere #{{$co->ReferenceNbr}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="alert alert-warning">
						Accepted file types: .jpg .pdf
					</p>
					<form action="/wo/file" method="post" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="nbr" value="{{$co->ReferenceNbr}}">
						<input type="hidden" name="id" value="{{$co->id}}">
						<input type="file" class="form-control" name="attachment" id="attachment">
						<hr>
						<button class="btn btn-success loading" type="submit">Upload File</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	@endforeach







	<div class="modal fade modal-lg" id="newFileModal" tabindex="-1" aria-labelledby="newFileModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="invoiceModalLabel">File Upload</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="alert alert-warning">
						Accepted file types: .pdf .jpg .jpeg .png .dwg .zip
					</p>
					<form action="/contract/file" method="post" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="nbr" value="{{$contract->SubcontractNbr}}">
						<input type="hidden" name="id" value="{{$contract->id}}">
						<input type="file" class="form-control" name="attachment" id="attachment">
						<hr>
						<button class="btn btn-success loading" type="submit">Upload File</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	@stop

	@section('scripts')

	<script>
		$(document).ready(function () {
			$('.currency').on('change click keyup input paste',(function (event) {
				$(this).val(function (index, value) {
					return value.replace(/(?!\.)\D/g, "")
					.replace(/(?<=\..*)\./g, "")
                          .replace(/(?<=\.\d\d).*/g, "")
					.replace(/\B(?=(\d{3})+(?!\d))/g, "");
				});
			}));
		});

		$(document).ready( function () {
			$('.datatable-bills').DataTable({
				"autoWidth": false,
				"columns": [
					@if ($contract->Accepted == 1)
					{ "width": "5%" },
					@endif
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "25%" },
					],
			});


			$('.datatable-wos').DataTable({
				"autoWidth": false,
				"columns": [
					@if ($contract->Accepted == 1)
					{ "width": "8%" },
					@endif
					{ "width": "8%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "25%" },
					{ "width": "5%" },
					{ "width": "25%" },
					],
			});


			$('.datatable-details').DataTable({
				"autoWidth": false,
				"columns": [
					{ "width": "5%" },
					{ "width": "25%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "5%" },
					{ "width": "35%" },
					],
			});




		} );

		$( document ).ready(function() {
			var accepted = {{$contract->Accepted}}
			var acceptModal = new bootstrap.Modal(document.getElementById('acceptModal'))
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



		$('.woLineItem').on('click', function() {
			if($(this).is(':checked'))
			{
				$('#lineAmount'+$(this).val()).removeAttr('disabled');
				$('#desc'+$(this).val()).removeAttr('disabled');
			}else{
				$('#lineAmount'+$(this).val()).attr('disabled',true);
				$('#desc'+$(this).val()).attr('disabled',true);
			}
		});


	</script>
	@stop
