<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;

class ActiveUserController extends Controller
{
    public function index()
    {
        return view('active_users');
    }

    public function data()
    {
        $users = UserActivity::with('user')
            ->selectRaw('DISTINCT ON (user_id) *')
            ->orderBy('user_id')
            ->orderByDesc('last_activity')
            ->get();

        return response()->json($users);
    }
}