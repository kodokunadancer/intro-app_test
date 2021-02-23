<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    /**
     * テンプレートを返す.
     * @return view
     */
    public function index()
    {
        $exitCode = Artisan::call('config:cache');
        return view('index');
    }

    /**
     * ログインユーザーの取得.
     * @return User
     */
    public function getUser()
    {
        return Auth::user();
    }

}
