<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\CreateUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getUser(Request $request)
    {
        return $request->user();
    }

    public function createUser(CreateUserRequest $request)
    {
        $data = [
            'user_type' => 'Member',
            'name' => $request->name,
            'email' => $request->email,
            'package_id' => $request->package_id,
            'expired_date' => $request->expired_date
        ];

        $id = DB::table('users')->insertGetId(array_merge($data, [
            'status' => '1',
            'password' => bcrypt($request->password),
        ]));

        if ($id) {
            return response()->json($data);
        }

        return response()->json([
            'error' => 'Unable to handle the request.',
        ], 503);
    }

    public function updateUser(UpdateUserRequest $request, $userId)
    {
        if (! $userId) {
            return response()->json([
                'error' => 'User not found.'
            ], 404);
        }

        if (! $request->hasAny(['name', 'package_id', 'expired_date'])) {
            return response()->json([
                'error' => 'Nothing provided for updating.'
            ], 400);
        }

        if (! $user = DB::table('users')->find($userId)) {
            return response()->json([
                'error' => 'User not found.'
            ], 404);
        }

        $authenticatedUser = $request->user();

        if ($authenticatedUser->id != $user->parent_user_id) {
            return response()->json([
                'error' => 'Bad request.'
            ], 400);
        }

        if ($user->status != '1' || $user->deleted != '0') {
            return response()->json([
                'error' => 'User can not be updated.'
            ], 400);
        }

        $data = $request->only(['name', 'package_id', 'expired_date']);

        if (count($data) > 0) {
            DB::table('users')->where('id', $userId)->update($data);

            return response()->json($data);
        }

        return response()->json([
            'error' => 'Unable to handle the request.',
        ], 503);
    }
}
