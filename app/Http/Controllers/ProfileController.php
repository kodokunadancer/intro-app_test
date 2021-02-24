<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProfile;
use App\Http\Requests\EditProfile;
use App\User;
use App\Profile;
use App\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    /**
     * プロフィール編集.
     * @param User        $user
     * @param EditProfile $request
     * @return \Illuminate\Http\Response
     */
    public function editMyProfile(User $user, EditProfile $request)
    {
        $profile = $user->profiles()->first();

        //画像編集処理
        if ($request->photo) {

            $extension = $request->photo->extension();

            $profile_photo = new Photo();

            // インスタンス生成時に割り振られたランダムなID値と
            // 本来の拡張子を組み合わせてファイル名とする
            $profile_photo->filename = $profile_photo->random_id . '.' . $extension;

            $profile_photo->filename = Storage::cloud()->putFileAs('profiles', $request->photo, $profile_photo->filename, 'public');

            // データベースエラー時にファイル削除を行うため
            // トランザクションを利用する
            DB::beginTransaction();

            try {
                $profile->photos()->delete();
                $profile->photos()->save($profile_photo);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                // DBとの不整合を避けるためアップロードしたファイルを削除
                Storage::cloud()->delete($profile_photo->filename);
                throw $exception;
            }
        }

        //名前と自己紹介の編集処理
        if ($user->id == $profile->user_id) {
            $profile->name = $request->textName;
            $profile->introduction = $request->textIntroduction;
            $profile->save();
        }

        return response($profile, 201);
    }
}
