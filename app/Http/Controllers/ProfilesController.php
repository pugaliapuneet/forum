<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            // 'activities' => $this->getActivity($user),
            'activities' => \App\Activity::feed($user),
        ]);
    }
}
