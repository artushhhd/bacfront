<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class LikeController extends Controller
{
    public function toggle(int $id)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $like = Like::where('product_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();
            return response()->json(['status' => 'unliked']);
        }

        Like::create([
            'user_id' => $userId,
            'product_id' => $id
        ]);

        return response()->json(['status' => 'liked']);
    }
}
