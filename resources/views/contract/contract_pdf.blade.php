<!DOCTYPE html>
<html>
<head>
    <title>{{$contract->Project->Description}}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</head>

<style>

    body{
       font-family: 'Montserrat', sans-serif;
   }

   table {
      width: 100%;
      font-size:12px;
  }

  th, td {
      padding: 3px;
      text-align: left;
      border-bottom: 1px solid #ddd;
  }


</style>
<body>
    <div class="container-fluid">
        <div class="row">
            <table>
                <tr>
                    <td> <h2 class="section-color shadow-sm">{{$contract->Project->Description}}</h2></td>
                    <td><h2 style="text-align: right;">Labor Contract</h2></td>
                </tr>
            </table>
           
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-bordered table-sm">
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
            <div class="col-md-6">
                <table class="table table-striped table-bordered table-sm">
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
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md">
                    <h3 class="section-color">Details</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable table-sm">
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
        </div>

    </body>
    </html>