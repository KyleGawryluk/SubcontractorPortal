<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }


    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $response = Http::post(config('api.URL').'auth/login',[
           'name'    =>$request->input('username'),
           'password'=>$request->input('password'),
           'tenant'  =>config('api.tenant'),
           'branch'  =>config('api.branch')
       ]);

        $body = json_decode($response->body());

        // echo "<pre>";
        // print_r($response);
        // echo "</pre>";
        // exit;

        switch ($response->status()) {
           case 204:
           Cookie::queue('acu_cookie', $this->buildCookie($response), 60);
           return redirect('/contracts')->withSuccess('Login Success');
           break;

           case 401:
           return back()->withErrors($body->exceptionMessage);
           break;

           case 403:
           return back()->withErrors($body->exceptionMessage);
           break;

           case 429:
           return back()->withErrors($body->exceptionMessage);
           break;

           case 500:
           return back()->withErrors($body->exceptionMessage);
           break;

           default:
           return back()->withErrors($response);
           break;
       }
   }


   public function logout()
   {
    $response = Http::withHeaders([
        'Cookie' => Cookie::get('acu_cookie'),
    ])->post(config('api.URL').'auth/logout');

    Cookie::forget('acu_cookie');

    return redirect('/')->withSuccess('You are logged out');
}


public function buildCookie($response)
{
    $headers = $response->headers();

    $cookie = '';
    $cookie .= strtok($headers['Set-Cookie'][3], ';').';';
    $cookie .= strtok($headers['Set-Cookie'][0], ';').';';
    $cookie .= strtok($headers['Set-Cookie'][2], ';').';';
    $cookie .= strtok($headers['Set-Cookie'][1], ';').';';
    $cookie .= strtok($headers['Set-Cookie'][8], ';').';';
    $cookie .= strtok($headers['Set-Cookie'][9], ';').';';

    return $cookie;
}

}
