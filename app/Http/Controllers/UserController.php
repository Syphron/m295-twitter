<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\TweetResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        return UserResource::make(User::find($id));
    }

    public function tweets($id)
    {
        $user = User::where('id', $id)->first();
        $tweets = $user->tweets()->take(10)->latest()->get();
        return TweetResource::collection($tweets);

    }

    public function me(Request $request)
    {
        return UserResource::make($request->user());
    }

    public function updateMe(UpdateUserRequest $request)
    {
        $user = $request->user();

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return UserResource::make($user);
    }

    public function deleteMe(Request $request)
    {
        $request->user()->delete();
        return response()->json(['message' => 'User Deleted']);
    }
}
