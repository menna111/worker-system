<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\Client\WorkerRegisterRequest;
use App\Models\Client;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:client', ['except' => ['login', 'register']]);
    }

    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('client')->attempt($credentials);

        if (! $token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public function register(WorkerRegisterRequest $request)
    {
        $photo = $this->uploadImage($request->photo, 'clients', 50);
        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photo,
        ]);

        return response()->json([
            'message' => 'Client created successfully',
            'user' => $client,
        ]);
    }

    public function logout()
    {
        Auth::guard('client')->logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
        ]);
    }
}
