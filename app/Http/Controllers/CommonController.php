<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

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

}
