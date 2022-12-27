<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class DataController extends Controller
{
    public function parseResponse($response)
    {
     $body = json_decode($response->body());

        // echo "<pre>";
        // print_r($response->status());
        // echo "</pre>";
        // exit;

     switch ($response->status()) {
         case 200:
         return $response;
         break;

         case 401:
         return redirect('/')->withErrors($body->exceptionMessage);
         break;

         case 403:
         return redirect('/')->withErrors($body->exceptionMessage);
         break;

         case 429:
         return redirect('/')->withErrors($body->exceptionMessage);
         break;

         case 500:
         return redirect('/')->withErrors($body->exceptionMessage);
         break;

         default:
         return redirect('/')->withErrors('Oops, Something Went Wrong');
         break;
     }

 }

 public function convertToObject($dataset)
 {
    $jdata = json_decode($dataset);
    $parsed = new \stdClass();
    $index = 0;

    foreach ($jdata as $key => $data) {
        $parsed->$key = new \stdClass();
        foreach ($data as $field => $value) {
            if (isset($value->value)) {
                $parsed->$key->$field = $value->value;
            }else{
                $parsed->$key->$field = null;
            }
        }

        $index++;
    }

    return $parsed;
}



public function convertToSingleObject($dataset)
{
    $jdata = json_decode($dataset);
    $parsed = [];

    foreach ($jdata as $key => $data) {

        if (is_object($data)) {
            $parsed[$key] = $this->parseObject($parsed,$key,$data);
        }elseif(is_array($data)){
            $parsed[$key] = $this->parseArray($parsed,$key,$data); 
        }else{
        $parsed[$key] = $this->parseData($parsed,$key,$data);
        }
    }

   return json_encode($parsed);
}



public function parseData($parsed,$key, $data)
{
    return $data;
      
}

public function parseObject($parsed,$key, $object)
{

    $newData = [];

    if (isset($object->value)) {
        $newData = $object->value;
    }else{
     foreach ($object as $index => $data) {

        if (isset($data->value)) {

         $newData[$index] = $data->value;
     }else{

         $newData[$index] = $data; 
     }
 }
}

return $newData;
}




public function parseArray($parsed,$key, $array)
{
    $newData = [];

    $count = 0;

    foreach ($array as $object) {

        if (isset($object->value)) {
            $newData[$key][$count] = $object->value;
        }else{
         foreach ($object as $index => $data) {

            if (isset($data->value)) {

             $newData[$count][$index] = $data->value;
         }else{

             $newData[$count][$index] = $data; 
         }
     }
 }
 $count++;
}
return $newData;
}
}
