<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GoodsReceivedDetails extends Model
{
    public function itemName(){
        return $this->belongsTo(ItemList::class, "item_id");
    }
    public function brandName(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function groupName(){
        return $this->belongsTo(Group::class, 'group_no');
    }
    public function gr_details_item(){
        return $this->belongsTo(GoodsReceived::class, "goods_received_no", "goods_received_no");
    }
}
