<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class AjaxStockController extends Controller
{
    public function search(Request $request)
    {
        return Stock::select(['id','hu1_no', 'item_code', 'expire_date', 'production_code', 'prima_erp_item_name'])
            ->where($request['field'],'=', $request['key'])
            ->where('status','on warehouse')
            ->groupBy('hu1_no')
            ->get();
    }

    public function outToCanvasser(Request $request)
    {
        return Stock::select(['id','hu1_no', 'item_code', 'expire_date', 'production_code', 'prima_erp_item_name'])
            ->where($request['field'],'=', $request['key'])
            ->update(['status' => 'on canvasser']);
    }
}
