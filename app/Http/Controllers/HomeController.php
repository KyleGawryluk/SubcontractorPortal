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

       //  $response = Http::post(config('api.URL').'auth/login',[
       //     'name'    =>$request->input('username'),
       //     'password'=>$request->input('password'),
       //     'tenant'  =>config('api.tenant'),
       //     'branch'  =>config('api.branch')
       // ]);



        $response = Http::asForm()->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
        ->post(config('api.INSTANCE').'identity/connect/token',[
           'client_id'     => env('CLIENT_ID'),
           'client_secret' => env('CLIENT_SECRET'),
           'scope'         => 'api',
           'grant_type'    => 'password',
           'username'      => $request->input('username'),
           'password'      => $request->input('password')
       ]);


        switch ($response->status()) {
           case 200:

           $headers = $response->headers();

           Cookie::queue('acu_cookie', $headers['Set-Cookie'][0], 60);

           $body = json_decode($response->body());

         // echo "<pre>";
         // print_r($response->headers());
         // echo "</pre>";
         // exit;

           Cookie::queue('oauth', $response->body(), 60);
           Cookie::queue('token', $body->access_token, 60);

           $userinforeq = Http::withHeaders(['Authorization' => 'Bearer '.Cookie::get('token'),])
           ->withBody(json_encode(['Username' => ['value'=>$request->input('username') ]]), 'application/json')
           ->put(config('api.URL').'Subcontracts/20.200.001/UserInfo?$expand=UserInfoDetails');

           $userinfo = json_decode($userinforeq->body());
         // $userinfo = $userinfo->UserInfoDetails[0];

           // echo "<pre>";
           // print_r($userinfo);
           // echo "</pre>";
           // exit;

         // Cookie::queue('first_name',$userinfo->FirstName->value, 60);
         // Cookie::queue('last_name',$userinfo->LastName->value, 60);
         // Cookie::queue('full_name',$userinfo->FirstName->value.' '.$userinfo->LastName->value, 60);
         // Cookie::queue('account_id',$userinfo->BusinessAccount->value, 60);
         // Cookie::queue('account_name',$userinfo->AccountName->value, 60);


           Cookie::queue('first_name','Kyle', 60);
           Cookie::queue('last_name','Gawryluk', 60);
           Cookie::queue('full_name','Kyle Gawryluk', 60);
           Cookie::queue('account_id','SGHREDGLAZ', 60);
           Cookie::queue('account_name','SGH Redglaze', 60);

           // Cookie::queue('userinfo',json_encode($userinfo), 60);

           // echo "<pre>";
           // print_r($userinfo->UserInfoDetails);
           // echo "</pre>";
           // exit;

         // Cookie::queue('userinfo',json_encode($userinfo->UserInfoDetails), 60);

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
