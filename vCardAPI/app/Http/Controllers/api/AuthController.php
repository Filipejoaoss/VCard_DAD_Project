<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const CLIENT_ID = 2;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        request()->request->add([
            'grant_type' => 'password',
            'client_id' => CLIENT_ID,
            'client_secret' => env('CLIENT_SECRET'),
            'username' => $request->phone_number,
            'password' => $request->password,
            'scope'         => '',
        ]);

        $request = Request::create(env('PASSPORT_SERVER_URL'). '/oauth/token', 'POST');
        $response = Route::dispatch($request);
        $errorCode = $response->getStatusCode();

        if ($errorCode == '200') {
            return json_decode((string) $response->content(), true);
        } else {
            return response()->json(
                ['msg' => 'User credentials are invalid ' . $response],//todo remove debug porpose
                $errorCode
            );
        }
    }
    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
