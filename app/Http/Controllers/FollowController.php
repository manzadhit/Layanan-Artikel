<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\User;

class FollowController extends Controller
{
    public function toggleFollow(Request $request, $userId)
    {
        $request->validate([
            'followed_user_id' => 'required|exists:users,id',
        ]);

        $followedUserId = $request->input('followed_user_id');

        // Prevent following self
        if ($userId == $followedUserId) {
            return response()->json(['error' => 'You cannot follow yourself'], 422);
        }

        $follow = Follow::where('user_id', $userId)
            ->where('followed_user_id', $followedUserId)
            ->first();

        if ($follow) {
            $follow->delete();
            $isFollowing = false;
            $message = 'Unfollowed';
        } else {
            Follow::create([
                'user_id' => $userId,
                'followed_user_id' => $followedUserId
            ]);
            $isFollowing = true;
            $message = 'Following';
        }

        return response()->json([
            'status' => $message,
            'isFollowing' => $isFollowing
        ]);
    }
}
