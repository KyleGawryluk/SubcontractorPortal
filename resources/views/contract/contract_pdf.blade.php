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

td, th{
    vertical-align: top;
    text-align: left;
    padding: 3px;
}
.td5{
    width:5%;!important
}

.td10{
    width:10%;!important
}

.td40{
    width:40%;!important
}

.td45{
    width:45%;!important
}

.td50{
    width:50%;!important
}


.disclaimer{
    font-size:8px;!important
    color:#2E2E2E ;
}


.bottom {
  position: absolute;
  bottom: 85px;
}


.border-bottom {
    border-bottom: 1px solid #ddd;!important
}

hr {
  border-top: 1px solid #2E2E2E;!important
}

</style>
<body>
    <table>
        <tr>
            <td>
                <table>
                    <tr>
                        <td><img src="{{ asset('img/SGH_Concepts_Header.png') }}" alt="SGH_Concepts_Header.png" width="400px"></td>
                    </tr>
                    <tr>
                        <td><hr></td>
                    </tr>
                    <tr>
                        <td><strong>Accepted by:</strong> </td>
                    </tr>
                    <tr>
                        <td><strong>Accepted Date:</strong> </td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr>
                        <td colspan="2"><h2>Subcontract Purchase Order</h2></td>
                    </tr>
                    <tr>
                        <td>SUBCONTRACT NO:</td>
                        <td>{{$contract->SubcontractNbr}}</td>
                    </tr>
                    <tr>
                        <td>START DATE:</td>
                        <td>@date($contract->StartDate)</td>
                    </tr>
                    <tr>
                        <td>PROJECT NO:</td>
                        <td>{{$contract->Project->ProjectID}}</td>
                    </tr>
                    <tr>
                        <td>VENDOR REF. NO:</td>
                        <td>
                          @if (!empty($contract->VendorRef))
                          {{$contract->VendorRef}}
                          @endif
                      </td>
                  </tr>
              </table>
          </td>
      </tr>
  </table>
  <hr>
  <table>
      <tr>
          <td class="td50">
              <table>
                  <tr>
                      <td class="td5">FROM:</td>
                      <td class="td45">
                       @if (!empty($contract->Project->InstallationManager)) <br>
                       {{$contract->Project->InstallationManager}}
                       @endif
                       SGH Concepts <br>
                       742 N 109th Court <br>
                       Omaha, NE 68154
                   </td>
               </tr>
           </table>
       </td>
       <td class="td50">
          <table>
              <tr>
                  <td class="td5">PROJECT:</td>
                  <td class="td45">
                    {{$contract->Project->Description}} <br>
                    {{$contract->Project->Addresses->AddressLine1}} <br>
                    {{$contract->Project->Addresses->City}}, {{$contract->Project->Addresses->State}} {{$contract->Project->Addresses->PostalCode}}
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
  <td>
      <table>
          <tr>
            <td class="td5">TO:</td>
            <td class="td45">
                {{Cookie::get('account_name')}} <br>
            </td>
        </tr>
    </table>
</td>
<td rowspan="2">
  <table>
      <tr>
          <td class="td5">NOTES:</td>
          <td class="td45">{{$contract->note}}</td>
      </tr>
  </table>
</td>
</tr>
<tr>
  <td>
    <table>
        <tr>
            <td class="td5">ATTN:</td>
            <td class="td45"></td>
        </tr>
    </table>
</td>
</tr>
</table>

<hr>
<p class="disclaimer">
    During the performance of this subcontract, the contractor agrees to comply with all Federal, state and local laws with respect to discrimination in employment and non-segregation of facilities including, but not limited to, requirements set out at 41 CFR 60-1.4, 60-250.5 and 60-741.5 which equal opportunity clauses are hereby incorporated in reference. <br>
    Subcontractor agrees to provide and maintain a current certificate of insurance (COI) on file naming SGH Concepts/SGH Redglaze as certificate holder.  The COI policy coverages must be in force for the full duration of the project period.  Terms and Conditions as defined in the executed SGH Concepts/SGH Redglaze Subcontractor Agreement applies to this project.
</p>
<hr>

<table>
    <thead>
        <th>Description</th>
        <th>Qty</th>
        <th>Contract Amount</th>
        <th>Billed Amount</th>
        <th>Unbilled Amount</th>
        <th>Retained Amount</th>
        <th>Notes</th>
    </thead>
    @foreach ($contract->SubcontractLines as $detail)
    <tr style="border-bottom: 1px solid black;">
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
<hr>
<table class="" style="margin-top: 50px;">
    <tr>
        <td>
            Signed:__________________________________________ <br>
            @if (!empty($contract->Project->InstallationManager)) <br>
            {{$contract->Project->InstallationManager}}
            @endif
        </td>
        <td>
            <table>
                <tr>
                    <td>SubTotal:</td>
                    <td>@currency($contract->SubcontractTotal)</td>
                </tr>
                <tr>
                    <td>Tax:</td>
                    <td>0.00</td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td><strong>@currency($contract->SubcontractTotal)</strong></td>
                </tr>
            </table>
        </td>
    </tr>

</table>
</body>
</html>