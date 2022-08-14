<?php

namespace App\Http\Controllers\backend;

use App\Brand;
use App\GoodsReceived;
use App\GoodsReceivedDetails;
use App\Http\Controllers\Controller;
use App\ItemList;
use App\Purchase;
use App\PurchaseDetail;
use App\PurchaseReturnDetail;
use Illuminate\Http\Request;

class PurchaseReturnDetailController extends Controller
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
    public function purchase_return_item_store(Request $request){
        $request->validate([
            'item_id'=>'required',
            'return_qty'=>'required',
            'comment'=>'required',
        ]);
        $gr_list = GoodsReceived::orderBy("id", "desc")->where("po_no", $request->purchase_no)->get();
        $return_qty = $request->return_qty;
        foreach($gr_list as $gr){
            if($return_qty > 0){
                $gr_received = GoodsReceivedDetails::where("goods_received_no", $gr->goods_received_no)->where("item_id", $request->item_id)->first();
                $return_item = new PurchaseReturnDetail;
                $return_item->purchase_return_no = $request->purchase_return_no;
                $return_item->gr_no = $gr_received->goods_received_no;
                $return_item->item_id = $request->item_id;
                $return_item->received_qty = $gr_received->received_qty;
                if($return_qty > $gr_received->received_qty){
                    $return_qty -= $gr_received->received_qty;
                    $return_item->return_qty = $gr_received->received_qty;
                }else{
                    $return_item->return_qty = $return_qty;
                    $return_qty = 0;
                }
                $return_item->comment = $request->comment;
                $save = $return_item->save();
            }
        }
        if ($save) {
            $temp_items = PurchaseReturnDetail::where('purchase_return_no', $request->purchase_return_no)->get();
            return view('backend.purchase-return.return_tempList', compact('temp_items'));
        } else {
            return response()->json(['error' => 'Item List is not add!']);
        }
    }
    public function pt_item_barcode(Request $request){
        $item_barcode = ItemList::find($request->item_id);
        $brands = Brand::find($item_barcode->brand_id);
        $purchase_no = PurchaseDetail::op_receive_qrt($request->purchase_no, $request->item_id);
        return [$brands,$item_barcode,$purchase_no];
    }
    public function temp_return_item_list_delete(PurchaseReturnDetail $id){
        $id->delete();
        return back();
    }
}
