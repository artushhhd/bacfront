<?php

namespace App\Http\Controllers;

use App\Models\{User, Product};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'stats' => [
                'users_count'    => User::count(),
                'products_count' => Product::count(),
            ],
            'recent' => [
                'users'    => User::latest()->limit(5)->get(),
                'products' => Product::latest()->limit(5)->get(),
            ]
        ]);
    }

    public function users(): JsonResponse
    {
        return response()->json([
            'total' => User::count(),
            'items' => User::latest()->get()
        ]);
    }

    public function deleteProduct(int $id): JsonResponse
    {
        $actor = Auth::user();
        $product = Product::findOrFail($id);

        if (!in_array($actor->role, ['moderator', 'admin', 'superadmin'])) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $product->delete();
        return response()->json(['message' => 'Resource deleted successfully']);
    }
    public function deleteUser(int $id): JsonResponse
{
    $actor = Auth::user();
    $target = User::findOrFail($id);

    $actorRole = strtolower($actor->role);
    $targetRole = strtolower($target->role);

    if ($target->id === $actor->id) {
        return response()->json(['error' => 'Cannot delete your own account'], 403);
    }

    if ($actorRole === 'superadmin') {
        if ($targetRole === 'superadmin') {
            return response()->json(['error' => 'Superadmins are protected'], 403);
        }
    } elseif ($actorRole === 'admin') {
        if (in_array($targetRole, ['admin', 'superadmin'])) {
            return response()->json(['error' => 'You cannot delete staff members'], 403);
        }
    } elseif ($actorRole === 'moderator') {
        if ($targetRole !== 'user') {
            return response()->json(['error' => 'Moderators can only delete basic users'], 403);
        }
    } else {
        return response()->json(['error' => 'Elevated privileges required'], 403);
    }

    $target->delete();
    return response()->json(['message' => 'User account terminated']);
}

}
