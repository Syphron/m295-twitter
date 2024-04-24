<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTweetRequest;
use App\Http\Resources\TweetResource;
use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    public function index()
    {
        try {
            /* 
            $tweets = Tweet::orderBy('created_at', 'desc')
                ->take(100)
                ->get();

             $tweets = $tweets->map(function ($tweet) {
                    $tweet->user = [
                        'id' => $tweet->user->id,
                        'name' => $tweet->user->name,
                    ];
                    return $tweet;
                }); 
            */

            $tweets = Tweet::with('user')->take(100)->latest()->get();

            return TweetResource::collection($tweets);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch tweets'], 500);
        }
    }

    public function store(StoreTweetRequest $request)
    {
        $tweet = new Tweet();
        $tweet->text = $request->text;
        $tweet->user_id = $request->user()->id;
        $tweet->save();

        return new TweetResource($tweet);
    }

    public function like($id)
    {
        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json(['message' => 'Tweet not found'], 404);
        }

        $tweet->likes = $tweet->likes + 1;
        $tweet->save();
    }
}
