<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ItemList extends Model
{
    protected $table = "items";
    protected $fillable = ['style_id', 'group_no', 'group_name', 'barcode', 'item_name', 'brand_id', 'country', 'unit', 'description', 'sell_price', 'vat_rate', 'vat_amount', 'total_amount'];
    
    public function brandName(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function groupName(){
        return $this->belongsTo(Group::class, 'group_no');
    }
    public function style(){
        return $this->belongsTo(Style::class, 'style_id');
    }
    
    public function itemOpenningStock()
    {
        return $this->hasOne('App\OpeningStock','item_id');
    }

    public function itemOpenningStockMonth($month)
    {
        return $this->hasMany(OpeningStockRecord::class,'item_id')->where('month',$month)->first();
    }


    public function itemStockPurchase()
    {
        return $this->hasMany('App\StockTransection', 'item_id');
    }

    public function itemStockQuantityPurch($itm)
    {
        $purchase=StockTransection::where('item_id', $itm->id)->where('stock_effect', 1)->sum('quantity');
        return $purchase;
    }

    public function thisMontItemPurch($itm)
    {
        $purchase=StockTransection::where('item_id', $itm->id)->where('stock_effect', 1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('quantity');
        return $purchase;
    }

    public function MontItemPurch($itm,$month)
    {
        $year=substr($month, 0, 4);
        $month=substr($month, 5, 8);
        $purchase=StockTransection::where('item_id', $itm->id)
        ->where('stock_effect', 1)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->sum('quantity');
        return $purchase;
    }


    public function itemStockQuantitySale($itm)
    {
        $purchase=StockTransection::where('item_id', $itm->id)->where('stock_effect', -1)->sum('quantity');
        return $purchase;
    }


    public function thisMonthItemSale($itm)
    {
        $purchase=StockTransection::where('item_id', $itm->id)->where('stock_effect', -1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('quantity');
        return $purchase;
    }

    public function MonthItemSale($itm,$month)
    {
        $year=substr($month, 0, 4);
        $month=substr($month, 5, 8);
        $purchase=StockTransection::where('item_id', $itm->id)
        ->where('stock_effect', -1)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->sum('quantity');
        return $purchase;
    }

    public function thisMonthItemSaleReturn($itm)
    {
        $saleReturn=StockTransection::where('item_id', $itm->id)->where('tns_type_code',"T")->where('stock_effect', 1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('quantity');
        return $saleReturn;
    }


    public function MonthItemSaleReturn($itm,$month)
    {
        $year=substr($month, 0, 4);
        $month=substr($month, 5, 8);
        $saleReturn=StockTransection::where('item_id', $itm->id)
        ->where('tns_type_code',"T")
        ->where('stock_effect', 1)
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->sum('quantity');

        return $saleReturn;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vatRate()
    {
        return $this->belongsTo(VatRate::class,'vat_rate');
    }
}
