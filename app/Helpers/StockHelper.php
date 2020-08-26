<?php

namespace App\Helpers;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockHelper
{
    public static function getAllCity(Array $where = [])
    {
        $data = Stock::query();
        if(count($where)) $data->where($where);

        return $data->select(['cluster','micro_cluster','city'])
            ->where('status','on warehouse')
            ->groupBy('city')->paginate();
    }

    public static function getAllItemCode(Array $where = [])
    {
        $data = Stock::query();
        if(count($where)) $data->where($where);

        return $data->select(['item_code','prima_erp_item_name','description', DB::raw('count(id) as qty')])
            ->where('status','on warehouse')
            ->groupBy('item_code')->paginate();
    }

    public static function getBY(Array $select, Array $group_by, Array $where = []  )
    {
        $data = Stock::query();
        if(count($where)) $data->where($where);

        $select[] = DB::raw('count(id) as qty');
        return $data
            ->select($select)
            ->where('status','on warehouse')
            ->groupBy($group_by)->paginate();
    }
}
