<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function updatePreferences(Request $request)
    {
        $user = User::find($request->user()->id);

        $preferences = [
            'preferred_sources' => $request->preferred_sources,
            'preferred_categories' => $request->preferred_categories,
            'preferred_authors' => $request->preferred_authors,
        ];

        $user->preferences = $preferences;
        $user->save();
        return response()->json(['message' => 'Preferences updated successfully']);
    }

    public function getPreferences(Request $request)
    {
        $user = User::find($request->user()->id);
        return response()->json(['preferences' => $user->nces]);
    }
}
