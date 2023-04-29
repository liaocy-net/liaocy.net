<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForeignShippingController extends Controller
{
    public function downloadMyCSV(Request $request){
        $user = $request->user();
        $foreignShippings = $user->foreignShippings;
        $csv = "重量(KG),費用(USD)\n";
        foreach($foreignShippings as $foreignShipping){
            $csv .= $foreignShipping->weight_kg . ",";
            $csv .= $foreignShipping->usd_fee . "\n";
        }
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="kokusai.csv"',
        ];
        return response($csv, 200, $headers);
    }
}
