<?php

namespace App\Http\Controllers\backend;

use App\Brand;
use App\Http\Controllers\Controller;
use App\ItemList;
use App\OpeningStock;
use App\OpeningStockRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockPositionController extends Controller
{
    public function stockPosition()
    {
        // dd(Carbon::now()->format('Y-m-d'));

        $stockP=OpeningStock::whereMonth('date','!=', Carbon::now()->month)->first();
       if($stockP)
       {
        $stockOp=OpeningStock::whereMonth('date','!=', Carbon::now()->month)->get();
        foreach($stockOp as $st)
       {
        $record=new OpeningStockRecord;
        $record->date=$st->date;
        $record->item_id=$st->item_id;
        $record->quantity=$st->quantity;
        $record->month=$st->month;
        $record->save();


        $st->date=Carbon::now()->format('Y-m-d');
        $st->quantity=($st->item->itemStockQuantityPurch($st->item)) - ($st->item->itemStockQuantitySale($st->item));
        $st->month=Carbon::now()->format('Y-m');
        $st->save();
       }

       }
        $brands=Brand::latest()->get();
        return view('backend.stock.stockPosition', compact('brands'));
    }


    public function searchStockPosition(Request $request)
    {
        $month=$request->month;
    
        if($month==Carbon::now()->format('Y-m'))
        {
            return redirect()->route('stockPosition');
        }
        $brands=Brand::latest()->get();
        return view('backend.stock.stockPositionMonth', compact('brands','month'));
    }
}
