<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return UserModel::all();
    }

    public function store(Request $request): JsonResponse
    {
        $user = UserModel::create($request->all());
        return response()->json(["data" => $user, "status" => 201]);
    }

    public function show(UserModel $user): UserModel
    {
        return $user;
    }

    public function update(Request $request, UserModel $user): UserModel
    {
        $user->update($request->all());
        return $user;
    }

    public function destroy(UserModel $user): JsonResponse
    {
        $user->delete();
        return response()->json([
            "success" => true,
            "message" => "Data terhapus"
        ]);
    }
}
