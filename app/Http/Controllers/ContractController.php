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
    ])->get(config('api.URL').'Subcontracts/20.200.001/Subcontract');

    // echo "<pre>";
    // print_r($response->body());
    // echo "</pre>";
    // exit;

     $dataController = new DataController();

     $response = $dataController->parseResponse($response);

     $contracts = $dataController->convertToObject($response->body());

     return view('contract.contracts', ['contracts'=>$contracts]);
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
    ])->get(config('api.URL').'Subcontracts/20.200.001/Subcontract/'.$id.'?$expand=SubcontractLines,Bills');

    $dataController = new DataController();

    // $response = $dataController->parseResponse($response);

    // echo "<pre>";
    // print_r(json_decode($response->body()));
    // echo "</pre>";
    // exit;

    $index = 0;

    $contract = $dataController->convertToSingleObject($response->body());

    $contract = json_decode($contract);

    $contract->Project = $this->getContractProject($contract->SubcontractLines[0]->ProjectProjectID);

    $contract->Project = json_decode($contract->Project);

    $contract = $this->checkBilling($contract);

    // echo "<pre>";
    // print_r($contract);
    // echo "</pre>";
    // exit;

    return $contract;
}


public function getContractProject($projectID)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Project/'.$projectID.'?$expand=Address,Addresses');

    $dataController = new DataController();

    $response = $dataController->parseResponse($response);

    $project = $dataController->convertToSingleObject($response->body());

    return $project;
}


public function createInvoice(Request $request)
{
    $data = [];

    $data['Vendor']['value'] = $request->input('vendor');
    $data['VendorRef']['value'] = $request->input('vendorRef');
    $data['Description']['value'] = $request->input('description');
    $data['Details'] = [];

    foreach ($request->input('lines') as $line) {
        $newLine = [];
        $newLine['POOrderType']['value'] = 'Subcontract';
        $newLine['POOrderNbr']['value'] = $request->input('contractNbr');
        $newLine['POLine']['value'] = $line['line'];
        $newLine['UnitCost']['value'] = $line['amount'];
        $newLine['Qty']['value'] = 1;
        $data['Details'][] = $newLine;
    }

    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.Cookie::get('token'),
    ])
    ->withBody(json_encode($data),'application/json')
    ->put(config('api.URL').'Subcontracts/20.200.001/Bill'.'?$select=ReferenceNbr,Details/POOrderNbr,Details/POOrderNbr,Details/InventoryID,Details/Qty&$expand=Details');

    // echo "<pre>";
    // print_r(json_decode($response->body()));
    // echo "</pre>";
    // exit;



    return back();
}


public function checkBilling($contract)
{
    $total = $contract->SubcontractTotal;

    $billed = 0;

    foreach ($contract->Bills as $bill) {
       $billed += $bill->BilledAmt;
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
