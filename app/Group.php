<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['group_no', 'group_name'];
    public function use_size($style_id){
        return $this->hasOne(ItemList::class, "group_name", "group_name")->where("style_id", $style_id)->first();
    }
    public function item_qty($style_id, $color_id){
        $item_info =  $this->hasOne(ItemList::class, "group_name", "group_name")->where("style_id", $style_id)->where("brand_id", $color_id)->first();
        if($item_info){
            $stock = StockTransection::where("item_id", $item_info->id)->where("stock_effect", 1)->get();
            // dump($stock->id);
            if($stock){
                return $stock->sum("quantity");
            }
        }
    }
}
