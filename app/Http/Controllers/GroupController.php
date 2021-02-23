<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Photo;
use App\Profile;
use App\User;
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

}
