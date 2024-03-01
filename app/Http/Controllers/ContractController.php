<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataController;
use PDF;

class ContractController extends Controller
{
    public function getContracts()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.Cookie::get('token'),
        ])->get(config('api.URL')."Subcontracts/20.200.001/Subcontract?\$filter=Vendor eq '".Cookie::get('account_id')."'");

        $dataController = new DataController();

        $contracts = $dataController->convertToObject($response->body());

        $open_contracts = new \stdClass();

        $archived_contracts = new \stdClass();

        $i = 0;

        foreach ($contracts as $contract) {
            if ($contract->Status != 'On Hold') {
             if ($contract->SubcontractNbrStatus == 'N' ) {
               $open_contracts->$i = $contract;
           }else{
            $archived_contracts->$i = $contract;
        }
    }
    $i++;
}


// echo "<pre>";
// print_r($response->body());
// echo "</pre>";
// exit;

return view('contract.contracts', ['open_contracts'=>$open_contracts,'archived_contracts'=>$archived_contracts]);
}



public function uploadFile(Request $request)
{
    $request->validate([
     'attachment' => 'required|mimes:pdf,jpg,jpeg,png,gif,dwg,zip |max:5120',
 ]);

    $file = $request->file('attachment');
    $nbr = $request->input('nbr');
    $name = $file->getClientOriginalName();
    $mime = $file->getClientMimeType();
    $file->move('files',$name);
    $path = public_path('files/').$name;

    $content = file_get_contents($path);
    
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->withBody($content, $mime)
    ->withHeaders(['Content-Type' => $mime,'Accept'=> $mime])
    ->put(config('api.URL')."Subcontracts/20.200.001/Subcontract/".$nbr."/files/".$name);

    return redirect()->back()->with('status', $name.' has been uploaded');
}



public function getFile($id, $filename)
{
   $response = Http::withHeaders([
    'Authorization' => 'Bearer '.Cookie::get('token'),
])->get(config('api.URL')."Subcontracts/20.200.001/files/".$id);

   $file = $response->getBody()->getContents();

   $headers = [
    'Content-Type' => 'application/octet-stream',
    'Content-Disposition' => 'attachment; filename='.$filename.';',
];

return response()->streamDownload(function () use ($file){
 echo $file;
}, $filename,$headers);
}



public function uploadBillFile(Request $request)
{
    $request->validate([
       'attachment' => 'required|mimes:pdf,jpg,jpeg,png,gif |max:5120',
   ]);

    $file = $request->file('attachment');
    $nbr = $request->input('nbr');
    $name = $file->getClientOriginalName();
    $mime = $file->getClientMimeType();
    $file->move('files',$name);
    $path = public_path('files/').$name;

    $content = file_get_contents($path);
    
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->withBody($content, $mime)
    ->withHeaders(['Content-Type' => $mime,'Accept'=> $mime])
    ->put(config('api.URL')."Subcontracts/20.200.001/Bill/INV/".$nbr."/files/".$name);

    return redirect()->back()->with('status', $name.' has been uploaded');
}



public function getBillFile($id, $filename)
{
   $response = Http::withHeaders([
    'Authorization' => 'Bearer '.Cookie::get('token'),
])->get(config('api.URL')."Subcontracts/20.200.001/files/".$id);

   $file = $response->getBody()->getContents();

   $headers = [
    'Content-Type' => 'application/octet-stream',
    'Content-Disposition' => 'attachment; filename='.$filename.';',
];

return response()->streamDownload(function () use ($file){
 echo $file;
}, $filename,$headers);
}



public function uploadChangeOrderFile(Request $request)
{
    $request->validate([
       'attachment' => 'required|mimes:pdf,jpg,jpeg,png,gif |max:5120',
   ]);

    $file = $request->file('attachment');
    $nbr = $request->input('nbr');
    $name = $file->getClientOriginalName();
    $mime = $file->getClientMimeType();
    $file->move('files',$name);
    $path = public_path('files/').$name;

    $content = file_get_contents($path);
    
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->withBody($content, $mime)
    ->withHeaders(['Content-Type' => $mime,'Accept'=> $mime])
    ->put(config('api.URL')."Subcontracts/20.200.001/ChangeRequests/".$nbr."/files/".$name);

    return redirect()->back()->with('status', $name.' has been uploaded');
}



public function getChangeOrderFile($id, $filename)
{
   $response = Http::withHeaders([
    'Authorization' => 'Bearer '.Cookie::get('token'),
])->get(config('api.URL')."Subcontracts/20.200.001/files/".$id);

   $file = $response->getBody()->getContents();

   $headers = [
    'Content-Type' => 'application/octet-stream',
    'Content-Disposition' => 'attachment; filename='.$filename.';',
];

return response()->streamDownload(function () use ($file){
 echo $file;
}, $filename,$headers);
}




public function getContract($id)
{
    $contract = $this->buildContract($id);

    return view('contract.contract', ['contract'=>$contract]);
}



public function buildContract($id)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Subcontract/'.$id.'?$expand=SubcontractLines/files,Bills/files,ChangeOrders/files,files');

    $dataController = new DataController();

    $index = 0;

    $contract = $dataController->convertToSingleObject($response->body());

    $contract = json_decode($contract);

    $contract->Project = json_decode($this->getContractProject($contract->SubcontractLines[0]->ProjectProjectID));

    $contract->Bills = $this->getBills($contract);

    $contract = $this->checkBilling($contract);

    $contract = $this->parseCOs($contract);

    if (is_array($contract->AcceptedBy) && is_array($contract->AcceptedDate) && is_array($contract->Accepted)) {
     $contract->Accepted = 0;
 }else{
     $contract->Accepted = 1; 
 }

   // echo "<pre>";
   // print_r($contract);
   // echo "</pre>";
   // exit;

 return $contract;
}


public function getBills($contract)
{
    $dataController = new DataController();

    foreach ($contract->Bills as $index => $bill) {

     $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Bill/INV/'.$bill->ReferenceNbr.'?$expand=files');

     $contract->Bills[$index] = json_decode($dataController->convertToSingleObject($response->body()));
 }

 return $contract->Bills;
}



public function getContractProject($projectID)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Project/'.$projectID.'?$expand=Addresses');

    $dataController = new DataController();

    $project = $dataController->convertToSingleObject($response->body());

    return $project;
}






public function createChangeOrder(Request $request)
{
    $lines = $request->input('woLine');

    $parsedLines = [];

    foreach ($lines as $line) {
      if (isset($line['check'])) {
        $newLine = [];
        $newLine['ProjectTaskID']['value'] = $line['projectTask'];
        $newLine['CostCode']['value'] = $line['costCode'];
        $newLine['InventoryID']['value'] = $line['invId'];
        $newLine['Qty']['value'] = 1;
        $newLine['UnitCost']['value'] = $line['lineAmount'];
        $newLine['CommitmentType']['value'] = 'Subcontract';
        $newLine['PONbr']['value'] = $request->input('contractNbr');
        $newLine['POLineNbr']['value'] = $line['lineNbr'];
        $newLine['LineDescription']['value'] = $line['desc'];
        $newLine['Vendor']['value'] = Cookie::get('account_id');
        $parsedLines[$line['project']][] = $newLine;
    }
}

foreach ($parsedLines as $project => $lines) {
    $data = [];

    $data['ChangeDate']['value'] = date("m/d/Y");
    $data['CompletionDate']['value'] = date("m/d/Y");
    $data['Description']['value'] = $request->input('description');
    $data['Class']['value'] = 'Customer';
    $data['Customer']['value'] = Cookie::get('account_id');
    $data['ProjectID']['value'] = $project;
    $data['ExternalRefNbr']['value'] = $request->input('contractNbr');
    $data['DetailedDescription']['value'] = $request->input('detailedDescription');
    $data['Commitments'] = [];

    foreach ($lines as $line) {
        $data['Commitments'][] = $line;
    }


    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])
    ->withBody(json_encode($data),'application/json')
    ->put(config('api.URL').'Subcontracts/20.200.001/ChangeOrder');

    $changeOrder = json_decode($response->body());

    if (isset($workOrder->error)) {
     return back()->withErrors(['status'=>$changeOrder->error]);
 }

}


// $wo_data['entity']['id'] = $changeOrder->id;

// $action = Http::withHeaders([
//     'Authorization' => 'Bearer '.Cookie::get('token'),
// ])
// ->withBody(json_encode($wo_data),'application/json')
// ->post(config('api.URL').'Subcontracts/20.200.001/ChangeRequests/RemoveHold');

return back()->withSuccess(['status'=>'Work Order Request has been Created']);
}



public function acceptContract(Request $request)
{
    $data = [];
    $data['id'] = $request->input('id');
    $data['SubcontractNbr']['value'] = $request->input('nbr');
    $data['Accepted']['value'] = 1;
    $data['AcceptedBy']['value'] = $request->input('acceptedName');
    $data['AcceptedDate']['value'] = date("Y-m-d H:i:s",strtotime($request->input('acceptedDate')));
    
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])
    ->withBody(json_encode($data),'application/json')
    ->put(config('api.URL')."Subcontracts/20.200.001/Subcontract");

    return back()->withSuccess(['status'=>'Contract has been Accepted']);
}


public function createInvoice(Request $request)
{

    if ($request->input('totalAmount') <= 0) {
     return back()->withError('Invoice Amount Cannot be 0');
 }

 $data = [];

 $data['Vendor']['value'] = $request->input('vendor');
 $data['VendorRef']['value'] = $request->input('vendref');
 $data['Description']['value'] = $request->input('description');
 $data['Amount']['value'] = $request->input('totalAmount');
 $data['Details'] = [];

 foreach ($request->input('lines') as $line) {
    $newLine = [];
    $newLine['POOrderType']['value'] = 'Subcontract';
    $newLine['POOrderNbr']['value'] = $request->input('contractNbr');
    $newLine['POLine']['value'] = $line['line'];
    $newLine['UnitCost']['value'] = $line['amount'];
    $newLine['Qty']['value'] = 1;
    // $newLine['BranchID']['value'] = 'HEADOFFICE';
    $data['Details'][] = $newLine;
}

$response = Http::withHeaders([
    'Authorization' => 'Bearer '.Cookie::get('token'),
])
->withBody(json_encode($data),'application/json')
->put(config('api.URL').'Subcontracts/20.200.001/Bill'.'?$expand=Details');

$invoice = json_decode($response->body());


// echo "<pre>";
// print_r($invoice);
// echo "</pre>";
// exit;

$inv_data = ['entity'];

    // $inv_data['entity']['RefNbr'] = $invoice->ReferenceNbr->value;
    // $inv_data['entity']['DocType'] = 'INV';
$inv_data['entity']['id'] = $invoice->id;

$action = Http::withHeaders([
    'Authorization' => 'Bearer '.Cookie::get('token'),
])
->withBody(json_encode($inv_data),'application/json')
->post(config('api.URL').'Subcontracts/20.200.001/Bill/ReleaseFromHold');


// $pdf = $this->saveInvoice($invoice->id);

// echo "<pre>";
// print_r($pdf);
// echo "</pre>";
// exit;

// $request->validate([
//    'attachment' => 'required|mimes:pdf,jpg|max:2048',
// ]);

// $file = $request->file('attachment');
// $nbr = $request->input('nbr');
// $name = $file->getClientOriginalName();
// $mime = $file->getClientMimeType();
// $file->move('files',$name);
// $path = public_path('files/').$name;

// $response = Http::withHeaders([
//     'Authorization' => 'Bearer '.Cookie::get('token'),
// ])->attach('file', file_get_contents($path), $name)
// ->withHeaders(['Content-Type' => $mime])
// ->put(config('api.URL')."Subcontracts/20.200.001/Subcontract/".$nbr."/files/".$name);

return redirect()->back()->with('status', $name.' has been uploaded');



return back()->withSuccess('Invoice has been created');
}


public function checkBilling($contract)
{
    $total = $contract->SubcontractTotal;

    $billed = 0;

    foreach ($contract->Bills as $bill) {
     if ($bill->Status != 'Rejected') {
        $billed += $bill->Amount;
    }
}

if ($billed < $total) {
    $contract->BillComplete = 0;
}else{
   $contract->BillComplete = 1;
}

return $contract;
}



public function parseCOs($contract)
{
    $totalCoAmt = 0;

    // echo "<pre>";
    // print_r($contract);
    // echo "</pre>";
    // exit;


    foreach ($contract->SubcontractLines as $key => $line) {
        $contract->SubcontractLines[$key]->ChangeAmt = 0;
        foreach ($contract->ChangeOrders as $co) {
           if ($line->LineNbr == $co->POLineNbr) {
               $contract->SubcontractLines[$key]->ChangeAmt = $co->Amount;
           }
           $totalCoAmt += $co->Amount;
       }
   }

   $contract->ChangeOrderTotal = $totalCoAmt;

   $contract->OriginalContractAmt = $contract->SubcontractTotal - $totalCoAmt;

   return $contract;
}





public function printContract($id)
{
  $contract = $this->buildContract($id);

  $pdf = PDF::loadView('contract.contract_pdf',['contract'=>$contract])->setPaper('letter', 'portrait');

  return $pdf->download('SGH Concepts - '.$contract->SubcontractNbr.' - '.$contract->Project->Description.'.pdf');

  // return view('contract.contract_pdf', ['contract'=>$contract]);
}


public function printInvoice($id)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Bill/INV/'.$id.'?$expand=Details');

    $dataController = new DataController();

    $invoice = json_decode($dataController->convertToSingleObject($response->body()));

    $pdf = PDF::loadView('contract.invoice_pdf',['invoice'=>$invoice])->setPaper('letter', 'portrait');

    return $pdf->download('Invoice - '.$invoice->ReferenceNbr.'.pdf');
}


public function saveInvoice($id)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Bill/INV/'.$id.'?$expand=Details');

    $dataController = new DataController();

    $invoice = json_decode($dataController->convertToSingleObject($response->body()));

    $pdf = PDF::loadView('contract.invoice_pdf',['invoice'=>$invoice])->setPaper('letter', 'portrait');

    $pdf->save(public_path('files/').'Invoice - '.$invoice->ReferenceNbr.'.pdf');

    return public_path('files/').'Invoice - '.$invoice->ReferenceNbr.'.pdf';
}



public function printCO($id)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/ChangeOrder/'.$id.'?$expand=Commitments');

    $dataController = new DataController();

    $co = json_decode($dataController->convertToSingleObject($response->body()));

    $pdf = PDF::loadView('contract.co_pdf',['co'=>$co])->setPaper('letter', 'portrait');

    return $pdf->download('Change Order - '.$co->RefNbr.'.pdf');
}


public function parseChildren($parent,$child,$name)
{
    if (!is_array($child)) {
        $parent->name[$index] = $this->parseLines($data);
    }else{
        foreach ($contract->Billing as $line => $data) {
            $contract->billing[$index] = $this->parseLines($data);
            $index++;
        }
    }
}


public static function parseLines($dataset)
{
    $jdata = $dataset;
    $parsed = new \stdClass();
    $index = 0;

    if (is_null($jdata)) {
        return null;
    }

    foreach ($jdata as $key => $data) {

      $parsed->$key = new \stdClass();

      if (isset($data->value)) {
        $parsed->$key = $data->value;
    }else{
        $parsed->$key = $data;
    }

    $index++;
}

foreach ($parsed as $key => $value) {
 if (is_object($value)) {
     $parsed->$key = '';
 }
}

return $parsed;
}

}
