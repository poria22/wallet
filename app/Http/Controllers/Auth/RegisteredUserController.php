<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = \Illuminate\Support\Facades\Validator::make($request->all(), (new UserRegisterRequest())->rules());

        if ($data->fails()) {
            return response()->json([
                'message' => $data->errors(),
                'status' => 'failed',
                'data' => ''
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);

            return \response()->json([
                'message' => 'user was created',
                'status' => 'success',
                'data' => $user
            ], 201);

        } catch (\Exception $exception) {
            return \response()->json([
                'status' => 'failed',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
