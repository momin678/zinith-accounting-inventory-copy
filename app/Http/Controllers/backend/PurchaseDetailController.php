<?php

namespace App\Http\Controllers\backend;

use App\Brand;
use App\CostCenterType;
use App\Group;
use App\Http\Controllers\Controller;
use App\ItemList;
use App\PartyInfo;
use App\PayMode;
use App\PayTerm;
use App\ProjectDetail;
use App\Purchase;
use App\PurchaseDetail;
use App\PurchaseRequisition;
use App\PurchaseRequisitionDetail;
use App\PurchaseReturnDetail;
use App\PurchaseTemp;
use App\PurchseDetail;
use App\Style;
use App\TempPRNO;
use App\Unit;
use App\VatRate;
use Illuminate\Http\Request;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $latest = PartyInfo::withTrashed()->orderBy('id','DESC')->first();
        if ($latest) {
            $pi_code = preg_replace('/^PI-/', '', $latest->pi_code);
            ++$pi_code;
        } else {
            $pi_code = 1;
        }
        if ($pi_code < 10) {
            $cc = "PI-000" . $pi_code;
        } elseif ($pi_code < 100) {
            $cc = "PI-00" . $pi_code;
        } elseif ($pi_code < 1000) {
            $cc = "PI-0" . $pi_code;
        } else {
            $cc = "PI-" . $pi_code;
        }
        $temp_po = '';
        $exit_temp_no = TempPRNO::whereDate('created_at', '=', date('Y-m-d'))->max('po_no');
        if($exit_temp_no){
            $temp_po = $exit_temp_no+1;
        }else {
            $temp_po = date("Ymd").'01';
        }
        $new_po_no = new TempPRNO;
        $new_po_no->po_no = $temp_po;
        $new_po_no->save();

        $brands = Brand::all();
        $units = Unit::all();
        $vatRates = VatRate::all();
        $projects = ProjectDetail::all();
        $suppliers = PartyInfo::where('pi_type', "Supplier")->get();
        $payMode = PayMode::all();
        $payTerms = PayTerm::all();
        $groups = Group::all();
        $itemLists = ItemList::all();
        $pr_lists = PurchaseRequisition::whereDoesntHave('pr_info')->where('status', 2)->get();
        $product_purchases = Purchase::orderBy('id', 'DESC')->paginate(15);
        $pr_temps = PurchaseTemp::orderBy('id', 'DESC')->get();
        $costTypes=CostCenterType::get();
        return view('backend.item-purchase.index', compact('product_purchases', 'brands', 'units', 'vatRates', 'projects', 'suppliers', 'payMode', 'payTerms', 'groups', 'itemLists', 'pr_lists', 'new_po_no', 'costTypes', 'cc', 'pr_temps'));
    }
    public function po_filter(Request $request)
    {
        if($request->filter_value){
            $filter_value = $request->filter_value;
        }else{
            $filter_value = [];
        }
        $product_purchases = Purchase::whereIn("status", $filter_value)->orderBy('id', 'DESC')->get();
        return view('backend.item-purchase.filter-value', compact('product_purchases'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase_info = Purchase::find($id);
        $purchase_items = PurchaseDetail::where('purchase_no', $purchase_info->purchase_no)->get();
        $payMode = PayMode::all();
        $payTerms = PayTerm::all();
        $product_purchases = Purchase::orderBy('id', 'DESC')->paginate(15);
        return view('backend.item-purchase.show', compact('purchase_info', 'purchase_items', 'payMode', 'payTerms', 'product_purchases'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
    public function all_po_item_delete(Request $request){
        $purchase_no = $request->purchase_no;
        $purchaseTemp = Purchase::where("purchase_no", $purchase_no)->first();
        if($purchaseTemp == null){
            PurchaseDetail::whereIn('purchase_no',explode(",", $purchase_no))->delete();
        }
        $temp_items = PurchaseDetail::where('purchase_no', $request->purchase_no)->get();
        return view('backend.item-purchase.tempList', compact('temp_items'));
    }
    public function one_po_item_delete(Request $request){
        $id = PurchaseDetail::find($request->id);
        $save = $id->delete();
        if ($save) {
            $temp_items = PurchaseDetail::where('purchase_no', $request->purchase_no)->get();
            return view('backend.item-purchase.tempList', compact('temp_items'));
        } else {
            return response()->json(['error' => 'Item List is not submitted!']);
        }
    }
    public function supplier_information(Request $request)
    {
        $supplier_information = PartyInfo::find($request->supplier_id);
        return response()->json($supplier_information);
    }
    public function purchase_print($id){
        $purchase_info = Purchase::find($id);
        $supplier_info = PartyInfo::find($purchase_info->supplier_id);
        $purchase_items = PurchaseDetail::where('purchase_no', $purchase_info->purchase_no)->get();
        return view('backend.item-purchase.purchase-print-pdf', compact('purchase_info', 'purchase_items', 'supplier_info'));
    }
    public function pr_print($id){
        $purchase_info = PurchaseRequisition::find($id);
        $purchase_items = PurchaseRequisitionDetail::where('purchase_no', $purchase_info->purchase_no)->get();
        return view('backend.pdf.pr-print', compact('purchase_info', 'purchase_items'));
    }
    public function delete_previouse_temp_item(Request $request){
        $id = $request->purchase_no;
        $Purchase = Purchase::find($id);
        if($Purchase == null){
            PurchaseDetail::whereIn('purchase_no',explode(",", $id))->delete();
        }
        $temp_items = PurchaseDetail::where('purchase_no', $request->purchase_no)->get();
        return view('backend.ajax.tempList', compact('temp_items'));
    }
}
