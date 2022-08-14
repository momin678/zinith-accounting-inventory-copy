<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseTemp extends Model
{
    public function partInfo(){
        return $this->belongsTo(PartyInfo::class, 'supplier_id');
    }
    public function projectInfo(){
        return $this->belongsTo(ProjectDetail::class, 'project_id');
    }
    public function payMode(){
        return $this->belongsTo(PayMode::class, 'pay_mode');
    }
    public function payTerm(){
        return $this->belongsTo(PayTerm::class, 'pay_term');
    }
    public function prInfo(){
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }
    public function itemName(){
        return $this->belongsTo(ItemList::class, 'item_id');
    }
    public function brandName(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function groupName(){
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function vatRate(){
        return $this->belongsTo(VatRate::class, 'vat_rate');
    }
    public function receive_qrt($po_no, $item_id){
        $gr_lists = GoodsReceived::where("po_no", $po_no)->get();
        $total_receive_qty = 0;
        foreach($gr_lists as $gr){
            $item_qty = GoodsReceivedDetails::Where("goods_received_no", $gr->goods_received_no)->where("item_id", $item_id)->first();
            $total_receive_qty += $item_qty->received_qty;
        }
        return $total_receive_qty;
    }
    public static function  op_receive_qrt($po_no, $item_id){
        $gr_lists = GoodsReceived::where("po_no", $po_no)->get();
        $total_receive_qty = 0;
        foreach($gr_lists as $gr){
            $item_qty = GoodsReceivedDetails::Where("goods_received_no", $gr->goods_received_no)->where("item_id", $item_id)->first();
            $total_receive_qty += $item_qty->received_qty;
        }
        return $total_receive_qty;
    }
    public function gr_details_check($po_no){
        $gr_list = GoodsReceived::where("po_no", $po_no)->where("status", 1)->get();
        $total_qty = 0;
        foreach($gr_list as $gr_no){
            $gr_qty = GoodsReceivedDetails::where('goods_received_no', $gr_no->goods_received_no)->sum('received_qty');
            $total_qty += $gr_qty;
        }
        return $total_qty;
    }
    public function purchase_details(){
        return $this->hasMany(PurchaseDetailTemp::class, 'purchase_no', 'purchase_no');
    }
}
