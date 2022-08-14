<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use  SoftDeletes;

    public function items($invoice_no)
    {
        $items=InvoiceItem::where('invoice_no', $invoice_no)->get();
        return $items;
    }

    public function project()
    {
        return $this->belongsTo(ProjectDetail::class,'project_id');

    }

    public function partyInfo($pi)
    {
        $pi=PartyInfo::where('pi_code',$pi)->first();
        return $pi;
    }

    public function taxbleSup($invoice_no)
    {
        return $this->items($invoice_no)->sum('total_unit_price');
    }

    public function vat($invoice_no)
    {
        return $this->items($invoice_no)->sum('vat_amount');
    }

    public function grossTotal($invoice_no)
    {
        return $this->items($invoice_no)->sum('cost_price');
    }

    public function itemStock($itm)
    {
        // return $itm;
        return $this->hasMany(StockTransection::class, 'transection_id')->where('item_id',$itm)->where('stock_effect', -1)->sum('quantity');
    }

    public function invoiceAmount()
    {
        return $this->hasOne(InvoiceAmount::class,'invoice_id');
    }
    //  work by mominul
    public function receipt_voucher($id){
        $paid_amount = ReceiptVoucher::where("tax_invoice_id", $id)->get();
        return $paid_amount->sum("paid_amount");
    }
}
