<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\Client\WorkerRegisterRequest;
use App\Services\WorkerAuthService;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Auth;

class WorkerAuthController extends Controller
{
    use ImageUploadTrait;

    public function __construct(private WorkerAuthService $workerAuthService)
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);
    }

    public function login(AuthRequest $request)
    {
        $data = $request->validated();
        $user = $this->workerAuthService->login($data);

        return $user;
    }

    public function register(WorkerRegisterRequest $request)
    {
        $data = $request->validated();
        $worker = $this->workerAuthService->register($data);

        return $worker;
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
