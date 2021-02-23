<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Photo;
use App\Profile;
use App\User;
use App\Http\Requests\CreateGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * グループ取得.
     * @param Group $group
     * @return Group
     */
    public function getGroup(Group $group)
    {
        $editGroup = Group::where('id', $group->id)->with('photo')->first();
        return $editGroup;
    }

    /**
     * グループ一覧.
     * @param User $user
     * @return Group
     */
    public function index(User $user)
    {
        if ($user->groups) {
            $my_groups = $user->groupUser()->with('photo')->get();
            return $my_groups;
        }
        return false;
    }

    /**
     * グループ作成.
     * @param User $user
     * @param CreateGroup $request
     * @return \Illuminate\Http\Response
     */
    public function create(User $user, CreateGroup $request)
    {
        $group = new Group();
        $group->name = $request->name;
        $group->password = $group->password;
        $user->groups()->save($group);
        $user->groupUser()->attach($group);
        return response($group, 201);
    }

}
