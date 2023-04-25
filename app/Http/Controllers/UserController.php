<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => DB::table('users')->orderBy("created_at", "desc")->paginate(env("PAGE_MAX_LIMIT", 50))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'string', 'max:255', 'regex:/^[admin|user]+$/u'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $params = $request->all();
        $params['password'] = Hash::make($params['password']);
        $params['email_verified_at'] = date('Y-m-d H:i:s');
        $params['name'] = $params['last_name'] . ' ' . $params['first_name'];

        User::create($params);

        // return back()->withErrors([
        //     'email' => 'メールアドレスまたはパスワードが間違っています。',
        // ]);

        return redirect()->route('users.index')->with('success', '登録しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id); 
        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params = $request->all();

        if ($params["act"] === "update_profile") {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'role' => ['required', 'string', 'max:255', 'regex:/^[admin|user|banned]+$/u'],
            ]);
    
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $params['name'] = $params['last_name'] . ' ' . $params['first_name'];
        } elseif ($params["act"] === "update_password") {
            $validator = Validator::make($request->all(), [
                'password' => ['required', Rules\Password::defaults()],
            ]);
    
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $params['password'] = Hash::make($params['password']);
        }

        $user = User::find($id); 
        $user->update($params); 
        return redirect()->route('users.show', $id)->with('success', '更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
