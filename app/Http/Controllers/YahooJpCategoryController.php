<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class YahooJpCategoryController extends Controller
{
    public function downloadYahooJpCategoryXLSX(Request $request){
        return Storage::download('yahoo_category_file/yahoo_category_file.csv');
    }

}
