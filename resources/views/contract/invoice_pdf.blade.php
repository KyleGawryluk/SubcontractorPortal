<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
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
            <td class="td50">
                <h2>{{Cookie::get('account_name')}}</h2>
                <p>{{Cookie::get('full_name')}}</p>
            </td>
            <td class="td50">
                <table>
                    <tr>
                        <td colspan="2"><h2>Invoice</h2></td>
                    </tr>
                    <tr>
                        <td class="td40"><strong>INVOICE NO:</strong></td>
                        <td>{{$invoice->ReferenceNbr}}</td>
                    </tr>
                    <tr>
                        <td class="td40"><strong>INVOICE DATE:</strong></td>
                        <td>@date($invoice->Date)</td>
                    </tr>
                    <tr>
                        <td class="td40"><strong>DUE DATE:</strong></td>
                        <td>@date($invoice->DueDate)</td>
                    </tr>
                    <tr>
                        <td class="td40"><strong>PROJECT NO:</strong></td>
                        <td>{{$invoice->Project}}</td>
                    </tr>
                    <tr>
                        <td class="td40"><strong>VENDOR REF. NO:</td>
                        <td>
                          @if (!empty($invoice->VendorRef))
                          {{$invoice->VendorRef}}
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
        <td class="td10"><strong>PAY TO:</strong></td>
        <td>
            {{Cookie::get('account_name')}} <br>
            Fill In Address <br>
            City State ZIP
        </td>
        <td class="td10"><strong>CUSTOMER:</strong></td>
        <td>
            SGH Concepts <br>
            742 N 109th Court <br>
            Omaha NE 68154
        </td>
    </tr>
</table>
<hr>
<table>
    <thead>
        <th>Description</th>
        <th>Cost Code</th>
        <th>Amount</th>
    </thead>
    @foreach ($invoice->Details as $detail)
    <tr style="border-bottom: 1px solid black;">
        <td>{{$detail->TransactionDescription}}</td>
        <td>{{$detail->CostCode}}</td>
        <td>@currency($detail->UnitCost)</td>
    </tr>
    @endforeach
</table>
<hr>
<table>
    <tr>
        <td class="td50"></td>
        <td class="td10">
            <strong>TOTAL:</strong> &nbsp; @currency($invoice->Amount)
        </td>
    </tr>
</table>
</body>
</html>