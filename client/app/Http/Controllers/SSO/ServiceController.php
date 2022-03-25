<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use  Illuminate\Support\Facades\Str;
use App\Models\User;
use Auth;


class ServiceController extends Controller
{
    public function getLogin(Request $request){

        $request->session()->put('state',$state = \Str::random(40));
        $query = http_build_query([
        "client_id" => " 95e85348-1891-410c-811c-d5ff0deceea2",
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
        "client_id" => " 95e85348-1891-410c-811c-d5ff0deceea2",
        "client_secret" => "SuJPizWeK2CoAhuhHL7Gb66jDofQ06Fmlh7uN3f7",
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

        $userArray = $response->json();
        try {
            $email = $userArray['email'];
        } catch (\Throwable $th) {
            return redirect("login")->withError("Falha ao obter as informações tente novamente");
        }
        $user = User::where("email", $email)->first();
        if (!$user) {
            $user = new User;
            $user->name = $userArray['name'];
            $user->email = $userArray['email'];
            $user->email_verified_at = $userArray['email_verified_at'];
            $user->save();
        }
        Auth::login($user);
        return view("home");
    }
}
