<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsReceived extends Model
{
    public function projectInfo(){
        return $this->belongsTo(ProjectDetail::class, 'project_id');
    }
    public function partInfo(){
        return $this->belongsTo(PartyInfo::class, 'supplier_id');
    }
    public function gr_qty_count(){
        return $this->hasMany(GoodsReceivedDetails::class, "goods_received_no", "goods_received_no");
    }
    public function invoice_posting_check(){
        return $this->hasOne(InvoicePosting::class, "goods_received_no", "goods_received_no");
    }
    public function po_item_info($item_id){
        return $this->belongsTo(PurchaseDetail::class, "po_no", "purchase_no")->where("item_id", $item_id)->first();
    }
}
