<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use  Illuminate\Support\Facades\Str;

class SSOController extends Controller
{
    public function getLogin(Request $request){

        $request->session()->put('state',$state = \Str::random(40));
        $query = http_build_query([
        "client_id" => "95e78691-37e1-4c7b-ab21-cc39526e0387",
        "redirect_uri" => "http://127.0.0.1:8080/callback",
        "response_type" => "code",
        "scope" => "view-user",
        "state" => $state
    ]);
    return redirect("http://127.0.0.1:8000/oauth/authorize?".$query);
    }
    public function getCallBack(Request $request){
        $state = $request->session()->pull("state");
        throw_unless(strlen($state) > 0 && $state == $request->state,InvalidArgumentException::class);
        $response = Http::asForm()->post(
        "http://127.0.0.1:8000/oauth/token",
        [
        'grant_type' => "authorization_code",
        "client_id" => "95e78691-37e1-4c7b-ab21-cc39526e0387",
        "client_secret" => "8tCRcx7cUH22Kb45vU1eq8Lqwq6ZIux1kdhV73cr  ",
        "redirect_uri" => "http://127.0.0.1:8080/callback",
        "code" => $request->code
        ]);
        $request->session()->put($response->json());
        return redirect(route('sso.connect'));

    }
    public function connectUser(Request $request){
        $access_token = $request->session()->get("access_token");
        $response = Http::withHeaders([
        "Accept" => "application/json",
        "authorization" => "Bearer ". $access_token
        ])->get("http://127.0.0.1:8000/api/user");

        return $response->json();
    }
}
