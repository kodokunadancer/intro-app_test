<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProfile;
use App\Profile;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * プロフィール作成.
     * @param CreateProfile $request
     * @return User
     */
    public function create(CreateProfile $request)
    {
        $my_profile = new Profile();
        $my_profile->name = $request->name;
        $my_profile->introduction = $request->introduction;
        $user = Auth::user();
        $user->profiles()->save($my_profile);
        return response($user, 201);
    }
}
