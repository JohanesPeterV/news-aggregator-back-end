<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function updatePreferences(Request $request, $userId)
{
    $user = User::find($userId);

    $preferences = [
        'preferred_sources' => $request->input('preferred_sources'),
        'preferred_categories' => $request->input('preferred_categories'),
        'preferred_authors' => $request->input('preferred_authors'),
    ];

    $user->preferences = $preferences;

    $user->save();

    return response()->json(['message' => 'Preferences updated successfully']);
}
}
