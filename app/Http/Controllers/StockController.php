<?php

namespace App\Http\Controllers;

use App\Imports\ImportListHU;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        "Prima ERP Item Name",
        "Cluster",
        "Micro Cluster",
        "City",
        "Canvasser",
        "RO",
    ];

    var $statuses = ['on warehouse','on canvasser','sold', 'expired'];
    public function index(Request $request)
    {
        $data = Stock::query();

        if ($request['s'])
        {
            foreach ($this->thead as $field)
            {
                $name = Str::snake(strtolower($field));
                if ( isset($request[$name]) ) {
                    $data = $data->where($name,$request['operator-'.$name],$request[$name]);
                }
            }
        }

        if($request['order'])
            $data = $data->orderBy($request['order']);
        if($request['status'])
            $data = $data->where('status','=',$request['status']);
        else
            $data = $data->where('status','=','on warehouse');
        $data = $data->paginate(25)->appends($request->all());

        return view('stock.index',['thead'=> $this->thead, 'statuses' => $this->statuses ,'data'=>$data]);
    }

    public function in()
    {
        return view('stock.in');
    }

    public function search()
    {
        return view('stock.search',['fields'=>$this->thead]);
    }

    public function Postsearch(Request $request)
    {
        $params = $request->all();
        unset($params['_token']);
        foreach ($this->thead as $field)
        {
            $name = Str::snake(strtolower($field));

            if( !$request[$name])
            {
                unset($params[$name]);
                unset($params['operator-'.$name]);
            }
        }
        return redirect(route('stock.list',$params));
    }


    public function stock_in(Request $request)
    {

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
