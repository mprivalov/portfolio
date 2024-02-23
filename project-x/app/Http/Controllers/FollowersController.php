<?php

namespace App\Http\Controllers;

use App\Models\Followers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowersController extends Controller
{
    function follower()
    {
        $user = Auth::user();
        $userToFollowId = request('id');
        $userToFollow = User::find($userToFollowId);
        $userFollowers = $userToFollow->followers;

        if ($userFollowers) {
            $userAlreadyFollow = $userToFollow->alreadyFollowing();
            if ($userAlreadyFollow) {
                $userAlreadyFollow->delete();
                return back()->withInput();
            }
        }

        $follow = new Followers();
        $follow->followers()->associate($user);
        $follow->following()->associate($userToFollow);
        $follow->save();

        return back()->withInput();
    }

    public function showFollowLists(Request $request)
    {
        $user = Auth::user();

        $followers = DB::table('followers')
            ->join('users', 'followers.follower_id', '=', 'users.id')
            ->where('followers.following_id', $user->id)
            ->select('users.name', 'users.id', 'users.avatar')
            ->get();

        $following = DB::table('followers')
            ->join('users', 'followers.following_id', '=', 'users.id')
            ->where('followers.follower_id', $user->id)
            ->select('users.name', 'users.id', 'users.avatar')
            ->get();

        return view('profile.follows', compact('user', 'followers', 'following'));
    }
}
