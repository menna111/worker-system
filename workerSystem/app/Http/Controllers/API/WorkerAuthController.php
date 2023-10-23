<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\Client\WorkerRegisterRequest;
use App\Models\Worker;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WorkerAuthController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);
    }

    public function login(AuthRequest $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('worker')->attempt($credentials);
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
        $photo = $this->uploadImage($request->photo, 'workers', 50);
        $worker = Worker::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photo,
            'phone' => $request->phone,
            'location' => $request->location,
        ]);

        return response()->json([
            'message' => 'Worker created successfully',
            'user' => $worker,
        ]);
    }

    public function logout()
    {
        Auth::guard('worker')->logout();

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
