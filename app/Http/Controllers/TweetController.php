<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Http\Requests\AddTweetRequest;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddTweetRequest $request)
    {
        $tweet = new Tweet();

        $tweet->user_id = $request->user()->id;
        $tweet->content = $request->content;

        try {
            $attachment = $request->file('attachment');
            $filename = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $attachment->getClientOriginalExtension();

            $path = $attachment->storeAs(
                'tweet-attachments', $filename . '-' . time() . '.' . $extension, 'public'
            );

            $tweet->attachment_url = config('app.url') . 'storage/' . $path;
        } catch (\Exception $e) {
            return response()->json([
                'success'       =>  false,
                'message'       =>  $e->getMessage()
            ]);
        }

        if ($tweet->save())
        {
            return response()->json([
                'success'       =>  true,
                'message'       =>  'Tweet saved.',
                'tweet'         =>  $tweet
            ]);
        } else {
            return response()->json([
                'success'       =>  false,
                'message'       =>  'Failed to save tweet.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function show(Tweet $tweet)
    {
        return response()->json([
            'tweet' =>  $tweet
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function edit(Tweet $tweet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tweet $tweet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tweet $tweet)
    {
        //
    }
}