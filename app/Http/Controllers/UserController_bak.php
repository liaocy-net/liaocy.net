<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * アプリケーションのすべてのユーザーを表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users', [
            'users' => DB::table('users')->orderBy("created_at", "desc")->paginate(100)
        ]);
    }

    /**
     * ユーザ登録
     *
     * @param Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }
}
