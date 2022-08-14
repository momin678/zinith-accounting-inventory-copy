<?php

namespace App\Http\Controllers\backend;

use App\Brand;
use App\GoodsReceived;
use App\GoodsReceivedDetails;
use App\Group;
use App\Http\Controllers\Controller;
use App\ItemList;
use App\Notification;
use App\PartyInfo;
use App\PayMode;
use App\PayTerm;
use App\ProjectDetail;
use App\Purchase;
use App\PurchaseDetail;
use App\PurchaseDetailTemp;
use App\PurchaseRequisition;
use App\PurchaseRequisitionDetail;
use App\PurchaseTemp;
use App\PurchseDetail;
use App\PurchseDetailTemp;
use App\StockTransection;
use App\Style;
use App\Unit;
use App\VatRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseTempController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'pr_id' => 'required',
            'supplier_id' => 'required',
            'tax_invoice_no' => 'required',
            'purchase_no' => 'required',
            'pay_mode' => 'required',
            'pay_term' => 'required',
            'pay_date' => 'required',
            'shipping_id' => 'required',
        ]);        
        $user_info = Auth::user();
        $pr_info = PurchaseRequisition::find($request->pr_id);
        $exit_pr_check = PurchaseTemp::where("pr_id", $request->pr_id)->first();
        if($exit_pr_check){
            $notification = array(
                'message' => 'This PR Item Already Take by Other!',
                'alert-type' => 'warning'
            );
            return back()->with($notification);
        }
        $exit_po_no = PurchaseTemp::whereDate("created_at", "=", date("Y-m-d"))->max("temp_purchase_no");
        $temp_po_no = '';
        if($exit_po_no){
            $temp_po_no = $exit_po_no+1;
        }else{
            $temp_po_no = date("Ymd").'01';
        }
        $new_po_no = $temp_po_no."PO";
        $purchase_items_temp = PurchaseDetailTemp::where('purchase_no', $request->purchase_no)->where('pr_no', $pr_info->purchase_no)->where('user_id', $user_info->id)->get();
        if($purchase_items_temp->isEmpty()){
            $notification = array(
                'message' => 'Please atleast one item add!',
                'alert-type' => 'warning'
            );
            return redirect('item-purchase')->with($notification);
        }
        $values = PurchaseDetailTemp::where('purchase_no', $request->purchase_no)->where('pr_no', $pr_info->purchase_no)->where('user_id', $user_info->id)->update(['purchase_no'=>$new_po_no]);
        if($values){
            $purchase_temp = new PurchaseTemp;
            $purchase_temp->project_id = $request->project_id;
            $purchase_temp->pr_id = $request->pr_id;
            $purchase_temp->supplier_id = $request->supplier_id;
            $purchase_temp->tax_invoice_no = $request->tax_invoice_no;
            $purchase_temp->tax_invoice_date = $request->tax_invoice_date;
            $purchase_temp->purchase_no = $new_po_no;
            $purchase_temp->temp_purchase_no = $temp_po_no;
            $purchase_temp->pay_mode = $request->pay_mode;
            $purchase_temp->pay_term = $request->pay_term;
            $purchase_temp->pay_date = $request->pay_date;
            $purchase_temp->shipping_id = $request->shipping_id;
            $purchase_temp->date = $request->date;
            $purchase_temp->user_id = $user_info->id;
            $purchase_temp->save();
            $notification = array(
                'message' => 'Purchase Temp Entry Successful!',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        }else{
            $notification = array(
                'message' => 'Some think Wrong! Please Try Again',
                'warning' => 'success'
            );
            return back()->with($notification);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase_temp_info = Purchase::find($id);
        $purchase_details_temps = PurchaseDetail::where('purchase_no', $purchase_temp_info->purchase_no)->get();
        $brands = Brand::all();
        $units = Unit::all();
        $vatRates = VatRate::all();
        $projects = ProjectDetail::all();
        $suppliers = PartyInfo::all();
        $payMode = PayMode::all();
        $payTerms = PayTerm::all();
        $groups = Group::all();
        $pr_lists = PurchaseRequisition::where('status', 2)->get();
        $product_purchases = Purchase::orderBy('id', 'asc')->paginate(15);
        $items = [];
        $item_ids = PurchaseRequisitionDetail::where("purchase_no", $purchase_temp_info->prInfo->purchase_no)->get();
        foreach($item_ids as $item){
            $itemInfo = ItemList::find($item->item_id);
            array_push($items, $itemInfo);
        }
        return view('backend.item-purchase.edit', compact('product_purchases', 'brands', 'units', 'vatRates', 'projects', 'suppliers', 'payMode', 'payTerms', 'groups', 'purchase_details_temps', 'purchase_temp_info', 'pr_lists', 'items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required',
            'pr_id' => 'required',
            'supplier_id' => 'required',
            'tax_invoice_no' => 'required',
            'purchase_no' => 'required',
            'pay_mode' => 'required',
            'pay_term' => 'required',
            'pay_date' => 'required',
            'shipping_id' => 'required',
        ]);
        $purchase_items_temp = PurchseDetailTemp::where('purchase_no', $request->purchase_no)->get();
        if($purchase_items_temp->isEmpty()){
            $notification = array(
                'message' => 'Please atleast one item add!',
                'alert-type' => 'warning'
            );
            return back()->with($notification);
        }
        $purchase_temp = PurchaseTemp::find($id);
        $purchase_temp->project_id = $request->project_id;
        $purchase_temp->pr_id = $request->pr_id;
        $purchase_temp->supplier_id = $request->supplier_id;
        $purchase_temp->tax_invoice_no = $request->tax_invoice_no;
        $purchase_temp->tax_invoice_date = $request->tax_invoice_date;
        $purchase_temp->purchase_no = $request->purchase_no;
        $purchase_temp->pay_mode = $request->pay_mode;
        $purchase_temp->pay_term = $request->pay_term;
        $purchase_temp->pay_date = $request->pay_date;
        $purchase_temp->shipping_id = $request->shipping_id;
        $purchase_temp->status = 0;
        $purchase_temp->save();
        $notification = array(
            'message' => 'Purchase Update Successful!',
            'alert-type' => 'success'
        );
        return redirect('item-purchase')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function purchase_temp_trasfer(Request $request){
        $id = Purchase::where("purchase_no", $request->purchase_no)->first();
        $purchase_trasfer = new Purchase;
        $purchase_trasfer->type = $id->type;
        $purchase_trasfer->po_list = $id->po_list;
        $purchase_trasfer->project_id = $id->project_id;
        $purchase_trasfer->pr_id = $id->pr_id;
        $purchase_trasfer->supplier_id = $id->supplier_id;
        $purchase_trasfer->tax_invoice_no = $id->tax_invoice_no;
        $purchase_trasfer->tax_invoice_date = $id->tax_invoice_date;
        $purchase_trasfer->purchase_no = $id->purchase_no;
        $purchase_trasfer->temp_purchase_no = $id->temp_purchase_no;
        $purchase_trasfer->pay_mode = $id->pay_mode;
        $purchase_trasfer->pay_term = $id->pay_term;
        $purchase_trasfer->pay_date = $id->pay_date;
        $purchase_trasfer->shipping_id = $id->shipping_id;
        $save = $purchase_trasfer->save();
        $purchase_requisition = PurchaseRequisition::where('purchase_no', $request->pr_id)->first();
        if($purchase_requisition){
            $purchase_requisition->status = 101;
            $purchase_requisition->save();
        }
        $goods_received = GoodsReceived::where("goods_received_no", $request->goods_received_no)->first();
        $goods_received->status = 1;
        $goods_received->save();
        if($save){
            $id->delete();
        }
        $items_ids = $request->item_id;
        $qtys = $request->qty;
        foreach($items_ids as $key => $item_id){
            $item = PurchseDetailTemp::where('purchase_no', $request->purchase_no)->where('item_id', $item_id)->first();
            $item_list = new PurchseDetail;
            $item_list->purchase_no = $item->purchase_no;
            $item_list->brand_id = $item->brand_id;
            $item_list->group_id = $item->group_no;
            $item_list->item_id = $item->item_id;
            $item_list->purchase_rate = $item->purchase_rate;
            $item_list->quantity = $qtys[$key];
            $item_list->unit = $item->unit;
            $item_list->vat_rate = $item->vat_rate;
            $item_list->vat_amount = $item->vat_amount;
            $item_list->total_amount = $item->total_amount;
            $item_list->taxable_supplies = $item->taxable_supplies;
            $save = $item_list->save();
            // item stock
            $item_stock = new StockTransection;
            $item_stock->item_id = $item->item_id;
            $item_stock->transection_id = $purchase_trasfer->id;
            $item_stock->quantity = $qtys[$key];
            $item_stock->stock_effect = 1;
            $item_stock->tns_type_code = "P";
            $item_stock->tns_description = "Purchase";
            $item_stock->save();
            $purchase_items_temp = PurchseDetailTemp::find($item->id);
            if($save){
                $purchase_items_temp->delete();
            }
        }
        $notification = array(
            'message' => 'Goods Received Successful!',
            'alert-type' => 'success'
        );
        return redirect('goods-received')->with($notification);
    }

    public function po_generation_approval_list(){
        $purchases = PurchaseTemp::where('status', 0)->get();
        return view('backend.item-purchase.po-generation-approval-list', compact('purchases'));
    }

    public function po_generation_approval_details($id){
        $purchase_info = PurchaseTemp::find($id);
        $purchase_items = PurchseDetailTemp::where('purchase_no', $purchase_info->purchase_no)->get();
        $payMode = PayMode::all();
        $payTerms = PayTerm::all();
        return view('backend.item-purchase.po-generation-approval-details', compact('purchase_info', 'purchase_items', 'payMode', 'payTerms'));
    }

    public function approve_po_submit($id){
        // dd($id);
        $purchase_info = PurchaseTemp::find($id);
        $purchase_info->status = 1;
        $purchase_info->save();
        $notification = array(
            'message' => 'PO Approve Successful!',
            'alert-type' => 'success'
        );
        return redirect('po-generation-approval-list')->with($notification);
    }
    public function approve_po_reviece(Request $request){
        $po_info = PurchaseTemp::where("purchase_no", $request->purchase_no)->first();
        $po_info->status = 99;
        $save = $po_info->save();
        if($save){
            $data = [
                ['purchase_id'=>$request->purchase_no, 'comment'=> $request->comment, 'state'=>"Editor", 'status'=>99],
            ];
            Notification::insert($data);
        }
        $notification = array(
            'message' => 'PO Revise Successful!',
            'alert-type' => 'success'
        );
        return redirect('po-generation-approval-list')->with($notification);
    }

    public function po_generation_revise_list(){
        $purchases = PurchaseTemp::where('status', 99)->get();
        return view('backend.item-purchase.po-generation-revise-list', compact('purchases'));
    }
    public function temp_po_item_store(Request $request){
        $request->validate([
            'purchase_no' => 'required',
            'item_list_id' => 'required',
            'purchase_rate' => 'required',
            'quantity' => 'required',
            'vat_rate' => 'required',
        ]);
        $user_info = Auth::user();
        $items = PurchaseRequisitionDetail::where("purchase_no", $request->pr_no)->where("style_code", $request->style_id)->get();
        if($items){
            foreach($items as $value){
                $exit_item = PurchaseDetailTemp::where("purchase_no", $request->purchase_no)->where("item_id", $value->item_id)->first();
                $vat_rate = VatRate::find($request->vat_rate)->value;
                $total = $request->purchase_rate * $value->quantity;
                $vat_amount = ($total * $vat_rate) / 100;
                $total_amount_with_amount = $total + $vat_amount;

                if($exit_item){
                    $exit_item->purchase_rate = $request->purchase_rate;
                    $exit_item->vat_rate = $request->vat_rate;
                    $exit_item->vat_amount = $vat_amount;
                    $exit_item->total_amount = $total_amount_with_amount;
                    $save = $exit_item->save();
                }else{
                    $item_info = ItemList::find($value->item_id);
                    $temp_item_store = new PurchaseDetailTemp;
                    $temp_item_store->purchase_no = $request->purchase_no;
                    $temp_item_store->pr_no = $request->pr_no;
                    $temp_item_store->brand_id = $item_info->brand_id;
                    $temp_item_store->group_id = $item_info->group_no;
                    $temp_item_store->item_id = $value->item_id;
                    $temp_item_store->purchase_rate = $request->purchase_rate;
                    $temp_item_store->quantity = $value->quantity;
                    $temp_item_store->unit = $value->unit;
                    $temp_item_store->vat_rate = $request->vat_rate;
                    $temp_item_store->vat_amount = $vat_amount;
                    $temp_item_store->total_amount = $total_amount_with_amount;
                    $temp_item_store->user_id = $user_info->id;
                    $save = $temp_item_store->save();
                }
            }
            $temp_items = PurchaseDetailTemp::where('purchase_no', $request->purchase_no)->where('pr_no', $request->pr_no)->get();
            return view('backend.item-purchase.tempList', compact('temp_items'));
            
        }else{
            $temp_items = PurchaseDetailTemp::where('purchase_no', $request->purchase_no)->where('pr_no', $request->pr_no)->get();
            return view('backend.item-purchase.tempList', compact('temp_items'));
        }
    }    
    public function one_po_item_edit(Request $request){
        $item_info = PurchaseDetailTemp::find($request->id);
        $item = ItemList::find($item_info->item_id);
        $style_info = Style::find($item->style_id);
        return response()->json([$item, $style_info, $item_info]);
    }
}
