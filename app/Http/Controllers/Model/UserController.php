<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\Model\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected function register(UserRegisterRequest $userRegisterRequest): JsonResponse
    {
        $data = $userRegisterRequest->validated();

        if (User::where('username', $data['username'])->count() == 1) {
            # code...
            # Pengecekan apakah ada di database...
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "Username " . $data['username'] . " already registered"
                    ],
                ],
            ], 422));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }
}
