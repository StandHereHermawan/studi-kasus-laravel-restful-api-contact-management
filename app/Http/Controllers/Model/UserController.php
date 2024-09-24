<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\Model\UserResource;
use App\Models\User;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Ramsey\Uuid\Rfc4122\UuidV7;

class UserController extends Controller
{
    public function register(UserRegisterRequest $userRegisterRequest): JsonResponse
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

    public function login(UserLoginRequest $userLoginRequest): UserResource
    {
        $data = $userLoginRequest->validated();

        $user = User::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Username or password wrong."
                    ],
                ]
            ], 401));
        }

        $user->token = (string) UuidV7::uuid7(Carbon::now());
        $user->save();

        return new UserResource($user);
    }

    public function get(Request $request): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $userUpdateRequest): UserResource
    {
        $data = $userUpdateRequest->validated();

        $user = Auth::user();

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return new UserResource($user);
    }
}
