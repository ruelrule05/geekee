<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FollowedUser;
use Illuminate\Http\Request;
use App\Http\Requests\FollowUserRequest;

class FollowedUserController extends Controller
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
        //
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
    public function store(FollowUserRequest $request)
    {
        if ($request->user()->id == $request->followed_user_id)
        {
            return response()->json([
                'success'       =>  false,
                'message'       =>  'You cannot follow yourself.'
            ]);
        }

        $followedUser = FollowedUser::where('user_id', $request->user()->id)
                                    ->where('followed_user_id', $request->followed_user_id)
                                    ->with('followedUser')
                                    ->first();

        if ($followedUser)
        {
            return response()->json([
                'success'       =>  false,
                'message'       =>  'You are already following ' . $followedUser->followedUser->name
            ]);
        }

        $followedUser = new FollowedUser();

        $followedUser->user_id = $request->user()->id;
        $followedUser->followed_user_id = $request->followed_user_id;

        if ($followedUser->save())
        {
            return response()->json([
                'success'       =>  true,
                'message'       =>  'You have followed ' . User::where('id', $request->followed_user_id)->pluck('name')[0]
            ]);
        } else {
            return response()->json([
                'success'       =>  false,
                'message'       =>  'Unable to follow the selected user.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FollowedUser  $followedUser
     * @return \Illuminate\Http\Response
     */
    public function show(FollowedUser $followedUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FollowedUser  $followedUser
     * @return \Illuminate\Http\Response
     */
    public function edit(FollowedUser $followedUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FollowedUser  $followedUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FollowedUser $followedUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FollowedUser  $followedUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(FollowedUser $followedUser)
    {
        //
    }
}
