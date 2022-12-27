<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DataController;

class ContractController extends Controller
{

 public function getContracts()
 {
    $response = Http::withHeaders([
        'Cookie' => Cookie::get('acu_cookie'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Subcontract');

    $dataController = new DataController();

    $response = $dataController->parseResponse($response);

    $contracts = $dataController->convertToObject($response->body());

    return view('contract.contracts', ['contracts'=>$contracts]);
}


public function getContract($id)
{
    $response = Http::withHeaders([
        'Cookie' => Cookie::get('acu_cookie'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Subcontract/'.$id.'?$expand=SubcontractLines,Billing');

    $dataController = new DataController();

    $response = $dataController->parseResponse($response);

    // echo "<pre>";
    // print_r(json_decode($response->body()));
    // echo "</pre>";
    // exit;

    $index = 0;

    $contract = $dataController->convertToSingleObject($response->body());

    $contract = json_decode($contract);

    $contract->Project = $this->getContractProject($contract->SubcontractLines[0]->ProjectProjectID);

    $contract->Project = json_decode($contract->Project);

    

    echo "<pre>";
    print_r($contract);
    echo "</pre>";
    exit;


    return view('contract.contract', ['contract'=>$contract]);
}


public function getContractProject($projectID)
{
    $response = Http::withHeaders([
        'Cookie' => Cookie::get('acu_cookie'),
    ])->get(config('api.URL').'Subcontracts/20.200.001/Project/'.$projectID.'?$expand=Address,Addresses');

    $dataController = new DataController();

    $response = $dataController->parseResponse($response);

    $project = $dataController->convertToSingleObject($response->body());

    //     echo "<pre>";
    // print_r(json_decode($project));
    // echo "</pre>";
    // exit;

    // $projectAddress = json_decode($response->body());

    // $project->address = $this->parseLines($projectAddress->Addresses);

    // // echo "<pre>";
    // // print_r($project);
    // // echo "</pre>";
    // // exit;

    return $project;
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

    // echo "<pre>";
    // print_r($jdata);
    // echo "</pre>";
    // exit;

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
