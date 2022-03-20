<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FollowedUser;
use Illuminate\Http\Request;
use App\Http\Requests\FollowUserRequest;
use App\Http\Requests\UnfollowUserRequest;
use Error;
use Exception;

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
        if (request()->user()->id == $followedUser->user_id)
        {
            return response()->json([
                'user'  =>  $followedUser->load('followedUser')
            ]);
        } else {
            return throw new Error('You are not currently following this person.');
        }
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
        
    }
    
    /**
     * unfollow
     *
     * @param  mixed $request
     * @return void
     */
    public function unfollow(UnfollowUserRequest $request)
    {
        $followedUser = FollowedUser::where('user_id', $request->user()->id)
                                    ->where('followed_user_id', $request->followed_user_id)
                                    ->with('followedUser')
                                    ->first();

        if ($followedUser)
        {
            if ($followedUser->delete())
            {
                return response()->json([
                    'success'       =>  true,
                    'message'       =>  'You have unfollowed ' . $followedUser->followedUser->name
                ]);
            } else {
                return response()->json([
                    'success'       =>  false,
                    'message'       =>  'Unable to unfollow user.'
                ]);
            }
        } else {
            return response()->json([
                'success'           =>  false,
                'message'           =>  'Cannot unfollow a user that you are not yet following.'
            ]);
        }
    }

    public function tweets(Request $request, FollowedUser $followedUser)
    {
        if (request()->user()->id == $followedUser->user_id)
        {
            return response()->json([
                'user'  =>  $followedUser->followedUser->tweets
            ]);
        } else {
            return throw new Error('You are not currently following this person.');
        }
    }

    public function suggested(Request $request)
    {
        $followedUsers = FollowedUser::where('user_id', $request->user()->id)->pluck('followed_user_id');

        $users = User::whereNotIn('id', $followedUsers)
                        ->whereNot('id', $request->user()->id)
                        ->paginate(5);

        return response()->json([
            'suggested' =>  $users
        ]);
    }
}
