<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\WhiteList;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WhiteListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = $request->input('q');

        if ($q) {
            $whiteLists = WhiteList::where('user_id', auth()->id())->where('brand', 'like', "%{$q}%")->get();
        } else {
            $whiteLists = WhiteList::where('user_id', auth()->id())->get();
        }

        return view('white_list.index', [
            'my' => User::find(auth()->id()),
            'whiteLists' => $whiteLists,
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

    /**
     * Store newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brands' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $brands = str_replace("\r\n", "\n", $request->brands);
            $brands = explode("\n", $brands);
            foreach ($brands as $brand) {
                if (mb_strlen($brand) > 255) {
                    throw new \Exception('ブランド名は250文字以内で入力してください。');
                }
            }

            $my = User::find(auth()->id());
            foreach ($brands as $brand) {
                // 重複チェック
                if ($my->whiteLists()->where('brand', $brand)->exists()) {
                    continue;
                }
                // 空文字チェック
                if (empty($brand)) {
                    continue;
                }

                $my->whiteLists()->create([
                    'brand' => $brand
                ]);
            }
            
            return redirect()->route('white_list.index')->with('success', 'ブランドを追加しました。');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Download white list as excel file.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadMyExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('brand');

        $user = auth()->user();
        $whiteLists = $user->whiteLists;
        foreach($whiteLists as $index => $whiteList){
            $sheet->setCellValue('A' . ($index + 1), $whiteList->brand);
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="white_list.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
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
        $validator = Validator::make($request->all(), [
            'tab1_amazon_id' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $params = $request->all();
            $my = User::find(auth()->id());
            foreach ($params['tab1_amazon_id'] as $brand) {
                $whiteList = $my->whiteLists()->where('brand', $brand)->first();
                if ($whiteList) {
                    $whiteList->delete();
                }
            }

            return redirect()->route('white_list.index')->with('success', 'ブランドを削除しました。');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}
