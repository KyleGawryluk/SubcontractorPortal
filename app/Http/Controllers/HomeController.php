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


    // public function login(Request $request)
    // {
    //     $this->validate($request, [
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);

    //     //Resource Owner Password Credentials
    //     $response = Http::asForm()->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
    //     ->post(config('api.INSTANCE').'identity/connect/token',[
    //      'client_id'     => env('CLIENT_ID'),
    //      'client_secret' => env('CLIENT_SECRET'),
    //      'scope'         => 'api',
    //      'grant_type'    => 'password',
    //      'username'      => $request->input('username'),
    //      'password'      => $request->input('password')
    //  ]);

    //     if ($response->status() == 400) {

    //         return redirect()->action([HomeController::class, 'passwordChange'], [$request])->with(['status'=>'You must change your password']);
    //     }

    //     if ($response->status() == 500) {

    //         return redirect()->back()->with(['error'=>'Your username and/or password are incorrect']);
    //     }

    //     $headers = $response->headers();

    //     Cookie::queue('acu_cookie', $headers['Set-Cookie'][0], 60);

    //     $body = json_decode($response->body());

    //     Cookie::queue('oauth', $response->body(), 60);
    //     Cookie::queue('token', $body->access_token, 60);
    //     Cookie::queue('username', $request->input('username'), 60);

    //     // $this->checkUser($request);
    
    //     return redirect('/contracts')->with(['status'=>'Login Success']);
    // }



    public function oauthLogin(Request $request)
    {
        Cookie::queue('oauth-code', $request->input('code'), 60);

        $response = Http::asForm()
        ->post(config('api.INSTANCE').'identity/connect/token',[
           'client_id'     => env('CLIENT_ID'),
           'client_secret' => env('CLIENT_SECRET'),
           'grant_type'    => 'authorization_code',
           'code'          => $request->input('code'),
           'redirect_uri'  => 'http://homestead.test/oauth-login',
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

        // $userinfo = $userinfo->UserInfoDetails[0];

        Cookie::queue('username',$userinfo->Login->value, 60);
        Cookie::queue('first_name',$userinfo->FirstName->value, 60);
        Cookie::queue('last_name',$userinfo->LastName->value, 60);
        Cookie::queue('full_name',$userinfo->FullName->value, 60);
        Cookie::queue('account_id',$userinfo->AccountID->value, 60);
        Cookie::queue('account_name',$userinfo->AccountName->value, 60);

        
        return redirect('/contracts')->with(['status'=>'Login Success']);
    }


    public function checkUser($request)
    {
        if (Cookie::get('token') != null && Cookie::get('username') != null && Cookie::get('account_id') == null) {

            $userinforeq = Http::withHeaders(['Authorization' => 'Bearer '.Cookie::get('token'),])
            ->withBody(json_encode(['Username' => ['value'=>Cookie::get('username') ]]), 'application/json')
            ->put(config('api.URL').'Subcontracts/20.200.001/UserInfo?$expand=UserInfoDetails');

            $userinfo = json_decode($userinforeq->body());
            $userinfo = $userinfo->UserInfoDetails[0];

            Cookie::queue('first_name',$userinfo->FirstName->value, 60);
            Cookie::queue('last_name',$userinfo->LastName->value, 60);
            Cookie::queue('full_name',$userinfo->FirstName->value.' '.$userinfo->LastName->value, 60);
            Cookie::queue('account_id',$userinfo->BusinessAccount->value, 60);
            Cookie::queue('account_name',$userinfo->AccountName->value, 60);

            $profile = Http::withHeaders(['Authorization' => 'Bearer '.Cookie::get('token'),])
            ->get(config('api.URL').'Subcontracts/20.200.001/MyProfile/'.$request->input('username'));

            $profileInfo = json_decode($profile->body());

            if ($profileInfo->ForceUsertoChangePasswordonNextLogin->value == 1) {

                return redirect('/change-pw')->with(['status'=>'You must change your password']);
            }


        }
    }

    public function passwordChange(Request $request)
    {
        $username = $request->input('username');
        return view('change-pw',['username'=>$username]);
    }



    public function changePW(Request $request)
    {
       $this->validate($request, [
        'username' => 'required',
        'old_password' => 'required',
        'new_password' => 'required|regex:^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$^',
        'confirm_password' => 'required|same:new_password',
    ],
    [
        'new_password.regex'=>'Your new password must contain: 1 lowercase letter, 1 uppercase letter, 1 number.',
        'confirm_password.same'=>'Your New Password and Confirmation must match.',
        'username.required' => 'It\'s 2023, you should know that Username is required.',
        'old_password.required' => 'Were you born yesterday? Old Password is required.',
        'new_password.required' => 'Didn\'t anyone teach you that New Password is required?',
        'confirm_password.required' => 'Really? Confirm Password is required.',
    ]
);

       $data = [];
       $data['Login']['value'] = $request->input('username');
       $data['OldPassword']['value'] = $request->input('old_password');
       $data['NewPassword']['value'] = $request->input('new_password');
       $data['ConfirmPassword']['value'] = $request->input('confirm_password');

    //  $response = Http::withHeaders([
    //     'Authorization' => 'Bearer '.Cookie::get('token'),
    // ])
    //  ->withBody(json_encode($data),'application/json')
    //  ->put(config('api.URL')."Subcontracts/20.200.001/Subcontract");
   }


   public function logout()
   {
    $response = Http::withHeaders([
        'Cookie' => Cookie::get('acu_cookie'),
    ])->post(config('api.URL').'auth/logout');

    Cookie::forget('acu_cookie');
    Cookie::forget('token');
    Cookie::forget('username');
    Cookie::forget('account_id');

    return redirect('/')->withSuccess(['msg'=>'You are logged out']);
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
