<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class UserDetails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
    //    if (Cookie::get('token') != null && Cookie::get('username') != null && Cookie::get('account_id') == null) {

    //     $userinforeq = Http::withHeaders(['Authorization' => 'Bearer '.Cookie::get('token'),])
    //     ->withBody(json_encode(['Username' => ['value'=>Cookie::get('username') ]]), 'application/json')
    //     ->put(config('api.URL').'Subcontracts/20.200.001/UserInfo?$expand=UserInfoDetails');

    //     $userinfo = json_decode($userinforeq->body());

    //     $userinfo = $userinfo->UserInfoDetails[0];

    //     Cookie::queue('first_name',$userinfo->FirstName->value, 60);
    //     Cookie::queue('last_name',$userinfo->LastName->value, 60);
    //     Cookie::queue('full_name',$userinfo->FirstName->value.' '.$userinfo->LastName->value, 60);
    //     Cookie::queue('account_id',$userinfo->BusinessAccount->value, 60);
    //     Cookie::queue('account_name',$userinfo->AccountName->value, 60);
    // }


    return $next($request);
}
}
