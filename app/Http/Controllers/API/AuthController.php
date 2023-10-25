<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\EmailVerificationEvent;

use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends AppBaseController
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:255', 
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // send verification mail to user
            EmailVerificationEvent::dispatch($user);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError("Registration failed, please try again. {$e->getMessage()}",500);
        }

        // $token = Auth::login($user);

        return $this->sendSuccess("User registered successfully. A verification link has been sent to {$user->email}");
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $loginCredentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!$token = auth()->attempt($loginCredentials)) {
            return $this->sendError('Unauthorized', 401);
        }

        $user = auth()->user();
        if (!$user->hasVerifiedEmail()) {
            return $this->sendError('Your account is not verified yet', 403);
        }

        $payload = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
        ];

        return $this->sendResponse($payload, 'Login successful');
    }


    public function verifyEmail($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return $this->sendError('Invalid verification url', 243);
        }

        $user = User::find($user_id);
        if (!$user) {
            return  response()->json([
                'status' => 'faild',
                'message' =>'User is not found',
            ]);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        } else {
            return $this->sendSuccess('Account is already verified');
        }

        return $this->sendSuccess('Account successfully verified');
    }
}
