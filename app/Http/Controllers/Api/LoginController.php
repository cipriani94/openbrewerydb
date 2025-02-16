<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    /**
     * Login api
     * 
     * Login with username and password
     */
    public function login(LoginRequest $loginRequest)
    {
        $validate = $loginRequest->validated();
        $user = User::where('username', $validate['username'])->first();

        if (Hash::check($validate['password'], $user->password)) {
            $expire = Carbon::now()->addSeconds(config('sanctum.expiration'));
            $token = $user->createToken($validate['username'] . " token ", ['*'], $expire)->plainTextToken;
            return response()->api(200, true, ['token' => $token, 'expire' => $expire]);
        }
        return response()->api(403, false, null, __('auth.failed'));
    }
}
