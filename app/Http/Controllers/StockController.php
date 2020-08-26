<?php

namespace App\Http\Controllers;

use App\Helpers\StockHelper;
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
        $pageTitle = 'Warehouse';
        $nav = [];
        $thead = ['Cluster', 'Micro Cluster', 'City'];
        $data = StockHelper::getAllCity();
        $targetField = 'city';

        return view('stock.index',compact('pageTitle', 'nav', 'thead', 'data', 'targetField'));
    }

    public function city($city)
    {
        $pageTitle = 'City: '.$city;
        $nav = ['Warehouse' => route('stock.index')];
        $thead = ['Item Code','prima_erp_item_name', 'Description',];
        $data = StockHelper::getAllItemCode(['city'=>$city]);
        $targetField = 'item_code';

        return view('stock.index',compact('pageTitle', 'nav', 'thead', 'data', 'targetField'));
    }

    public function item($city, $item_code)
    {
        $pageTitle = 'Item: '.$item_code;
        $nav = ['Warehouse' => route('stock.index'),
            'City: '.$city => route('stock.city',$city)];
        $thead = ['HU2 No', 'Expire Date'];
        $select = $this->setToSnackText($thead);
        $data = StockHelper::getBY($select,['hu2_no'], ['city'=>$city, 'item_code'=>$item_code]);
        $targetField = 'hu2_no';

        return view('stock.index',compact('pageTitle', 'nav', 'thead', 'data', 'targetField'));
    }

    public function hu2($city, $item_code,$hu2_no)
    {
        $pageTitle = 'HU2 No: '.$hu2_no;
        $nav = ['Warehouse' => route('stock.index'),
            'City: '.$city => route('stock.city',['city'=>$city]),
            'Item: '.$item_code => route('stock.item', ['city'=>$city, 'item_code'=>$item_code])];
        $thead = ['HU1 No', 'Expire Date'];
        $select = $this->setToSnackText($thead);
        $data = StockHelper::getBY($select,['hu1_no'], ['city'=>$city, 'item_code'=>$item_code, 'hu2_no'=>$hu2_no]);
        $targetField = 'hu1_no';

        return view('stock.index',compact('pageTitle', 'nav', 'thead', 'data', 'targetField'));
    }



    public function list(Request $request)
    {
        $data = StockHelper::getBY(['hu2_no'],['hu2_no'],['item_code'=>'SP0KAXHITZD-JKT']);
        dd($data);
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



    public function hu1($hu2_no, Request $request)
    {
        $columns = ['HU1 No'];
        $data = Stock::query();

        if($request['order'])
            $data = $data->orderBy($request['order']);
        if($request['status'])
            $data = $data->where('status','=',$request['status']);
        else
            $data = $data->where('status','=','on warehouse');
        $data = $data->where('hu2_no', $hu2_no)->paginate(25)->appends($request->all());

        return view('stock.h1',[
            'thead'=> $columns, 'statuses' => $this->statuses ,
            'hu2_no' => $hu2_no ,
            'data'=>$data]);
    }

    public function box($hu2_no,$hu1_no, Request $request)
    {
        $columns = ['IP', 'MSISDN No', 'Status', 'Expire Date'];
        $data = Stock::query();

        if($request['order'])
            $data = $data->orderBy($request['order']);
        if($request['status'])
            $data = $data->where('status','=',$request['status']);
        else
            $data = $data->where('status','=','on warehouse');
        $data = $data->where('hu1_no', $hu1_no)->paginate(25)->appends($request->all());

        return view('stock.sn_msisdn',[
            'thead'=> $columns, 'statuses' => $this->statuses ,
            'hu2_no' => $hu2_no ,
            'hu1_no' => $hu1_no ,
            'data'=>$data]);
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

    private function setToSnackText(array $thead): array
    {
        $snack_type = [];
        foreach ($thead as $item) {
            $snack_type[] = Str::snake(strtolower($item));
        }
        return $snack_type;
    }
}
