<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlackList;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class BlackListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('black_list.index', [
            'my' => User::find(auth()->id())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function downloadMyCSV(Request $request) {
        $params = $request->all();

        $validator = Validator::make($params, [
            'platform' => ['required', 'string', 'max:255', 'regex:/^[amazon|yahoo]+$/u'],
            'on' => ['required', 'string', 'max:255', 'regex:/^[brand|category|title|asin]+$/u']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $my = auth()->user();
        $blackLists = $my->blackLists()->where([
            ['platform', $params["platform"]],
            ['on', $params["on"]]
        ])->get();
        $csv = "";
        foreach($blackLists as $blackList){
            $csv .= $blackList->value . "\n";
        }
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"black_list_{$params["platform"]}_{$params["on"]}.csv\"",
        ];
        return response($csv, 200, $headers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function storeMultiple(Request $request)
    {
        try {
            $params = $request->all();

            $validator = Validator::make($params, [
                'platform' => ['required', 'string', 'max:255', 'regex:/^[amazon|yahoo]+$/u'],
                'on' => ['required', 'string', 'max:255', 'regex:/^[brand|category|title|asin]+$/u'],
                'values' => ['required', 'string'],
            ]);
    
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $platform = $params["platform"];
            $on = $params["on"];

            $values = str_replace("\r\n", "\n", $params["values"]);
            $values = explode("\n", $values);

            foreach ($values as $value) {
                if (mb_strlen($value) > 255) {
                    throw new \Exception('各値は250文字以内で入力してください。', 442);
                }
            }

            $my = User::find(auth()->id());
            foreach ($values as $value) {
                // 重複チェック
                if ($my->blackLists()->where([
                    ['platform', $platform],
                    ['on', $on],
                    ['value', $value],
                ])->exists()) {
                    continue;
                }
                // 空文字チェック
                if (empty($value)) {
                    continue;
                }

                $my->blackLists()->create([
                    'platform' => $platform,
                    'on' => $on,
                    'value' => $value,
                ]);
            }

            return response("ブラックリストを登録しました。", 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 442);
        }
    }

    public function getBlackLists(Request $request)
    {
        $platform = $request->input('platform');
        $on = $request->input('on');
        $q = $request->input('q');

        if ($q) {
            $blackLists = BlackList::where([
                ['user_id', auth()->id()],
                ['platform', $platform],
                ['on', $on],
                ['value', 'like', "%{$q}%"],
            ])->get();
        } else {
            $blackLists = BlackList::where([
                ['user_id', auth()->id()],
                ['platform', $platform],
                ['on', $on]
            ])->get();
        }
        return [
            'blackLists' => $blackLists,
            'platform' => $platform,
            'on' => $on,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function destroyMultiple(Request $request){
        

        try {
            $validator = Validator::make($request->all(), [
                'platform' => ['required', 'string', 'max:255', 'regex:/^[amazon|yahoo]+$/u'],
                'on' => ['required', 'string', 'max:255', 'regex:/^[brand|category|title|asin]+$/u'],
                'black_list_value' => ['required', 'array']
            ]);
    
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $params = $request->all();
            $my = User::find(auth()->id());
            foreach ($params['black_list_value'] as $value) {
                $whiteList = $my->blackLists()->where([
                    ['platform', $params['platform']],
                    ['on', $params['on']],
                    ['value', $value]
                ])->first();
                if ($whiteList) {
                    $whiteList->delete();
                }
            }

            return response("ブラックリストを削除しました。", 200);

        } catch (\Exception $e) {
            return response($e->getMessage(), 442);
        }
    }
}
