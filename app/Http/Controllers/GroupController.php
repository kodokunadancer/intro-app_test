<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Photo;
use App\Profile;
use App\User;
use App\Http\Requests\CreateGroup;
use App\Http\Requests\EditGroup;
use App\Http\Requests\SerchGroup;
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

    /**
     * グループ検索.
     * @param User $user
     * @param SerchGroup $request
     * @return Group
     */
    public function reserch(User $user, SerchGroup $request)
    {
        $group = Group::where([
        ['name', $request->group_name],
        ['password', $request->password],
      ])->with('photo')->first();

        $groups = $user->groups()->get()->all();
        $id_array = array_column($groups, 'id');

        //検索したグループが存在しない場合
        if (!$group) {
            return response(['error' => 'NotGroup'], 400);
        }
        //すでに参加している場合
        elseif (in_array($group->id, $id_array)) {
            return response(['error' => 'Participated'], 400);
        }
        return $group;
    }

    /**
     * グループ参加.
     * @param User  $user
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function join(User $user, Group $group)
    {
        //ユーザーとグループの紐付きを中間テーブルに保存する
        //すでに同じグループに参加している場合は、ロールバックする
        try {
            $user->groupUser()->attach($group);
            return response($group, 201);
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    /**
     * グループ編集.
     * @param User      $user
     * @param Group     $group
     * @param EditGroup $request
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Group $group, EditGroup $request)
    {
        if ($request->photo) {
            $extension = $request->photo->extension();

            $group_photo = new Photo();

            // インスタンス生成時に割り振られたランダムなID値と
            // 本来の拡張子を組み合わせてファイル名とする
            $group_photo->filename = $group_photo->random_id . '.' . $extension;

            $group_photo->filename = Storage::cloud()->putFileAs('groups', $request->photo, $group_photo->filename, 'public');

            // データベースエラー時にファイル削除を行うため
            // トランザクションを利用する
            DB::beginTransaction();

            try {
                $group->photo()->delete();
                $group->photo()->save($group_photo);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                // DBとの不整合を避けるためアップロードしたファイルを削除
                Storage::cloud()->delete($group_photo->filename);
                throw $exception;
            }
        }

        $group->name = $request->name;
        $group->save();

        return response($group, 201);
    }

    /**
     * グループ退会.
     * @param User  $user
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function exit(User $user, Group $group)
    {
        $group->users()->detach($user);
        return false;
    }

}
