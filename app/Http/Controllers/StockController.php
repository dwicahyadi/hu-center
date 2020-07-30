<?php

namespace App\Http\Controllers;

use App\Imports\ImportListHU;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class StockController extends Controller
{
    var $thead = [
        "PO No",
        "IP",
        "HU1 No",
        "HU2 No",
        "Description",
        "Item Code",
        "MSISDN No",
        "Expire Date",
        "ERP Item ID",
        "ERP Item Name",
        "Cluster",
        "Micro Cluster",
        "City",
        "Canvasser",
        "RO",
    ];

    var $statuses = ['on warehouse','on canvasser','sold', 'expired'];
    public function index()
    {
        $data = Stock::paginate(20);
        return view('stock.index',['thead'=> $this->thead, 'statuses' => $this->statuses ,'data'=>$data]);
    }

    public function in()
    {
        return view('stock.in');
    }

    public function stock_in(Request $request)
    {

//        dd($request->all());
        $data = Excel::toArray(new ImportListHU(), $request->file('file'));
        if($request != 'transfer')
        {
            $this->inFromPurchase($data[0]);
        }else{

        }

        return redirect()->back()->with('success','Berhasil menyimpan '.number_format(count($data[0])). ' record data');
    }

    /**
     * @param array $insert
     */
    private function inFromPurchase(array &$data)
    {
        foreach ($data as $item) {
            $item['cluster'] = \request('cluster');
            $item['micro_cluster'] = \request('micro_cluster');
            $item['city'] = \request('city');
            $item['prima_erp_item_name'] = \request('erp_item');
            $insert[] = $item;
        }
        $chunks = array_chunk($insert, 1000);

        foreach ($chunks as $chunk) {
            Stock::insertOrIgnore($chunk);
        }
    }
}
