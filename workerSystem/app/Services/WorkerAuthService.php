<?php

namespace App\Services;

use App\Models\Worker;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WorkerAuthService
{
    use ImageUploadTrait;

    public function login($data)
    {
        $token = Auth::guard('worker')->attempt($data);
        if (! $token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        $user = Auth::guard('worker')->user();
        $this->checkStatus($user);

        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public function register($data)
    {
        $photo = $this->uploadImage($data->photo, 'workers', 50);
        $worker = Worker::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'photo' => $photo,
            'phone' => $data->phone,
            'location' => $data->location,
        ]);

        return response()->json([
            'message' => 'Worker created successfully',
            'user' => $worker,
        ]);
    }

    private function checkStatus($user)
    {
        if ($user->status == 0) {
            return response()->json(['message' => 'Your Account Is Pending', 422]);
        }
    }
}
