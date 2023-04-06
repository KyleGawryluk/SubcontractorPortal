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

    // echo "<pre>";
    // print_r($response->body());
    // echo "</pre>";
    // exit;


    public function getContracts()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.Cookie::get('token'),
        ])->get(config('api.URL')."Subcontracts/20.200.001/Subcontract?\$filter=Vendor eq '".Cookie::get('account_id')."'");

        $dataController = new DataController();

        $response = $dataController->parseResponse($response);

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

    return view('contract.contracts', ['open_contracts'=>$open_contracts,'archived_contracts'=>$archived_contracts]);
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
    ])->get(config('api.URL').'Subcontracts/20.200.001/Subcontract/'.$id.'?$expand=SubcontractLines,Bills,ChangeOrders');

    $dataController = new DataController();

    $index = 0;

    $contract = $dataController->convertToSingleObject($response->body());

    $contract = json_decode($contract);

    $contract->Project = $this->getContractProject($contract->SubcontractLines[0]->ProjectProjectID);

    $contract->Project = json_decode($contract->Project);

    $contract = $this->checkBilling($contract);

    if (is_array($contract->AcceptedBy) && is_array($contract->AcceptedDate) && is_array($contract->Accepted)) {
       $contract->Accepted = 0;
   }else{
       $contract->Accepted = 1; 
   }

   return $contract;
}


public function getContractProject($projectID)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Project/'.$projectID.'?$expand=Addresses');

    $dataController = new DataController();

    $response = $dataController->parseResponse($response);

    $project = $dataController->convertToSingleObject($response->body());

    return $project;
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

    return back()->withSuccess(['msg'=>'Contract has been Accepted']);
}


public function createInvoice(Request $request)
{
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
        $newLine['BranchID']['value'] = 'TBGROUP';
        $data['Details'][] = $newLine;
    }

    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])
    ->withBody(json_encode($data),'application/json')
    ->put(config('api.URL').'Subcontracts/20.200.001/Bill'.'?$expand=Details');

    $invoice = json_decode($response->body());

    echo "<pre>";
    print_r(json_decode($response->body()));
    echo "</pre>";
    exit;


    $inv_data = ['entity'];

    // $inv_data['entity']['RefNbr'] = $invoice->ReferenceNbr->value;
    // $inv_data['entity']['DocType'] = 'INV';
    $inv_data['entity']['id'] = $invoice->id;

    $action = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])
    ->withBody(json_encode($inv_data),'application/json')
    ->post(config('api.URL').'Subcontracts/20.200.001/Bill/ReleaseFromHold');

    return back()->withSuccess('Invoice has been created');
}


public function checkBilling($contract)
{
    $total = $contract->SubcontractTotal;

    $billed = 0;

    foreach ($contract->Bills as $bill) {
       if ($bill->Status != 'Rejected') {
        $billed += $bill->BilledAmt;
    }
}

if ($billed < $total) {
    $contract->BillComplete = 0;
}else{
 $contract->BillComplete = 1;
}

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
