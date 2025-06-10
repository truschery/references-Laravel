<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $req){

        $validated = $req->validate([
            "username" => "required|string|max:255",
            "password" => "required|string",
            "client_id" => "required|string",
            "client_secret" => "required|string",

        ]);


        $credentials = request(['username', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                 'message' => 'Unauthorized',
                 'data' => null,
                 'timestamp' => time(),
                 'success' => false,
             ], 401);


        $response = Http::asForm()->post(url('http://references_nginx/oauth/token'), [
            'grant_type' => 'password',
            'client_id' => $req->client_id,
            'client_secret' => $req->client_secret,
            'username' => $req->username,
            'password' => $req->password,
            'scope' => '',
        ]);

        if ($response->failed()) {
            return response()->json([
                 'message' => 'Unauthorized',
                 'data' => null,
                 'timestamp' => time(),
                 'success' => false,
             ], 401);
        }


        return response()->json([
            'message' => 'Успешно',
            'data' => $response->json(),
            'timestamp' => time(),
            'success' => true,
        ], 200);




   }

   public function register(Request $req){
        try {
            $validated = $req->validate([
                "username" => "required|string|max:255",
                "password" => "required|string"
            ]);

            $user = new User;
            $user->username = $req->username;
            $user->password = bcrypt($req->password);
            $user->save();

            return response()->json([
                'message' => 'Успешно',
                'data' => $user,
                'timestamp' => time(),
                'success' => true,
            ], 200);
        }catch (QueryException $e) {

             return response()->json([
                 'message' => 'ServerError',
                 'data' => $e,
                 'timestamp' => time(),
                 'success' => false,
             ], 500);
        } catch (\Exception $e) {

             return response()->json([
                  'message' => 'ServerError',
                  'data' => $e,
                  'timestamp' => time(),
                  'success' => false,
             ], 500);
        }


   }
}
