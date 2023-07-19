<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\DataController;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }


    public function oauthLogin(Request $request)
    {
        Cookie::queue('oauth-code', $request->input('code'), 60);

        $response = Http::asForm()
        ->post(config('api.INSTANCE').'identity/connect/token',[
           'client_id'     => env('CLIENT_ID'),
           'client_secret' => env('CLIENT_SECRET'),
           'grant_type'    => 'authorization_code',
           'code'          => $request->input('code'),
           'redirect_uri'  => url('oauth-login'),
       ]);

        $headers = $response->headers();

        Cookie::queue('acu_cookie', $headers['Set-Cookie'][0], 60);

        $body = json_decode($response->body());

        Cookie::queue('oauth', $response->body(), 60);
        Cookie::queue('token', $body->access_token, 60);

        $myuser = Http::withHeaders(['Authorization' => 'Bearer '.$body->access_token,])
        ->withBody(json_encode(['Username' => ['value'=>'' ]]), 'application/json')
        ->put(config('api.URL').'Subcontracts/20.200.001/MyUser?$expand=MyUserDetails');

        $userinfo = json_decode($myuser->body());

        Cookie::queue('username',$userinfo->Login->value, 60);
        Cookie::queue('first_name',$userinfo->FirstName->value, 60);
        Cookie::queue('last_name',$userinfo->LastName->value, 60);
        Cookie::queue('full_name',$userinfo->FullName->value, 60);
        Cookie::queue('account_id',$userinfo->AccountID->value, 60);
        Cookie::queue('account_name',$userinfo->AccountName->value, 60);
        
        return redirect('/contracts')->with(['status'=>'Login Success']);
    }

    public function mirror()
    {
        return view('mirror');
    }

    public function loginAs(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|max:10',
        ]);

        $account = strtoupper($request->input('account_id'));

        Cookie::queue('account_id',$account, 5);

        return redirect('/contracts')->with(['status'=>'Login Success']);
    }


    public function logout()
    {
        $response = Http::withHeaders([
            'Cookie' => Cookie::get('acu_cookie'),
        ])->post(config('api.URL').'auth/logout');

        Cookie::queue(Cookie::forget('acu_cookie'));
        Cookie::queue(Cookie::forget('token'));
        Cookie::queue(Cookie::forget('username'));
        Cookie::queue(Cookie::forget('first_name'));
        Cookie::queue(Cookie::forget('last_name'));
        Cookie::queue(Cookie::forget('full_name'));
        Cookie::queue(Cookie::forget('account_id'));
        Cookie::queue(Cookie::forget('account_name'));
        Cookie::queue(Cookie::forget('oauth'));
        Cookie::queue(Cookie::forget('token'));
        Cookie::queue(Cookie::forget('oauth-code'));

        return redirect('/')->withSuccess(['msg'=>'You are logged out']);
    }
}
