<?php

namespace App\Http\Controllers\backend;

use App\Fifo;
use App\GoodsReceived;
use App\GoodsReceivedDetails;
use App\Http\Controllers\Controller;
use App\Notification;
use App\Purchase;
use App\PurchaseDetail;
use App\PurchaseReturn;
use App\PurchaseReturnDetail;
use App\StockTransection;
use App\TempPRNO;
use Illuminate\Http\Request;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Purchase::orderBy("id", "desc")->paginate(15);
        $return_lists = PurchaseReturn::orderBy('id', 'desc')->paginate(15);
        return view('backend.purchase-return.index', compact('purchases', 'return_lists'));
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
        $temp_items = PurchaseReturnDetail::where('purchase_return_no', $request->purchase_return_no)->get();
        if($temp_items->isEmpty()){
            return back()->with('error', 'At lest One Item select');
        }
        $exit_pr_no = PurchaseReturn::whereDate("created_at", "=", date("Y-m-d"))->max("temp_purchase_return_no");
        $temp_pt_no = '';
        if($exit_pr_no){
            $temp_pt_no = $exit_pr_no+1;
        }else{
            $temp_pt_no = date("Ymd").'01';
        }
        $pt_no = $temp_pt_no."PT";
        $values = PurchaseReturnDetail::where('purchase_return_no', $request->purchase_return_no)->update(['purchase_return_no'=>$pt_no]);
        if($values){
            $purchases_return = new PurchaseReturn;
            $purchases_return->purchase_return_no = $pt_no;
            $purchases_return->temp_purchase_return_no = $temp_pt_no;
            $purchases_return->po_no = $request->purchase_no;
            $purchases_return->project_id = $request->project_id;
            $purchases_return->supplier_id = $request->supplier_id;
            $purchases_return->date = $request->date;
            $purchases_return->state = "PT Editor";
            $purchases_return->status = 1;
            $save = $purchases_return->save();
            $notification = array(
                'message'=> "Item Return Create Successful",
                'alert-type' => 'success'
            );
            return redirect('purchase-return')->with($notification);
        }else{
            $notification = array(
                'message'=> "Some Thing Wrong! Please Try Again",
                'alert-type' => 'warning'
            );
            return redirect('purchase-return')->with($notification);
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
        $purchase_info = Purchase::find($id);
        $purchase_items = PurchaseDetail::where('purchase_no', $purchase_info->purchase_no)->get();
        $return_lists = PurchaseReturn::orderBy('id', 'desc')->paginate(15);
        return view('backend.purchase-return.show', compact('purchase_info', 'purchase_items', 'return_lists'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pt_info = PurchaseReturn::find($id);
        $pt_items = PurchaseReturnDetail::where('purchase_return_no', $pt_info->purchase_return_no)->get();
        $gr_items = GoodsReceivedDetails::where('goods_received_no', $pt_info->gr_no)->get();
        $return_lists = PurchaseReturn::where("status", 200)->get();
        return view('backend.purchase-return.edit', compact('pt_info', 'pt_items', 'return_lists', 'gr_items'));
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
            'purchase_return_no'=> 'required',
            'purchase_no'=> 'required',
            'goods_received_no'=> 'required',
            'purchase_return_no'=> 'required',
        ]);
        $temp_items = PurchaseReturnDetail::where('purchase_return_no', $request->purchase_return_no)->get();
        if($temp_items->isEmpty()){
            return back()->with('error', 'At lest One Item select');
        }
        $purchases_return = PurchaseReturn::find($id);
        $purchases_return->date = $request->date;
        $purchases_return->status = 1;
        $purchases_return->state = "PT Editor";
        $save = $purchases_return->save();
        if($save){
            $n = Notification::where("purchase_id", $request->purchase_return_no)->where("state", "PT Editor")->where("status", 99)->first();
            if($n){
                $n->status = 0;
            $n->save();
            }
        }
        $notification = array(
            'message'=> "Item Return Update Successful",
            'alert-type' => 'success'
        );
        return redirect('purchase-return')->with($notification);
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
    public function pt_details(PurchaseReturn $id){
        $pt_info = $id;
        $pt_items = PurchaseReturnDetail::where('purchase_return_no', $pt_info->purchase_return_no)->get();
        $return_lists = PurchaseReturn::orderBy('id', 'desc')->paginate(15);
        return view('backend.purchase-return.pt-details', compact('pt_info', 'pt_items', 'return_lists'));
    }
    public function pt_return(Purchase $id){
        $temp_pt = '';
        $exit_temp_no = TempPRNO::whereDate('created_at', '=', date('Y-m-d'))->max('pt_no');
        if($exit_temp_no){
            $temp_pt = $exit_temp_no+1;
        }else {
            $temp_pt = date("Ymd").'01';
        }
        $new_pt_no = new TempPRNO;
        $new_pt_no->pt_no = $temp_pt;
        $new_pt_no->save();

        $po_info = $id;
        $po_items = PurchaseDetail::where("purchase_no", $po_info->purchase_no)->get();
        $return_lists = PurchaseReturn::orderBy('id', 'desc')->paginate(15);
        return view('backend.purchase-return.create', compact('po_info', 'po_items', 'return_lists', 'new_pt_no'));
    }
    public function purchase_return_authorize(){
        $authrize_pt = PurchaseReturn::where('status', 0)->get();
        return view('backend.purchase-return.authorize-pt-list', compact('authrize_pt'));
    }
    public function pt_authorize_details(PurchaseReturn $id){
        $pt_info = $id;
        $pt_items = PurchaseReturnDetail::where('purchase_return_no', $pt_info->purchase_return_no)->get();
        $return_lists = PurchaseReturn::where("status", 200)->get();
        return view('backend.purchase-return.pt-authorize-details', compact('pt_info', 'pt_items', 'return_lists'));
    }
    public function authorize_pt_reviece(Request $request){
        $authorize_requisition_info = PurchaseReturn::where("purchase_return_no", $request->purchase_return_no)->first();
        $authorize_requisition_info->status = 99;
        $authorize_requisition_info->state = "PT Authorizer";
        $save = $authorize_requisition_info->save();
        if($save){
            $notification = new Notification;
            $notification->purchase_id = $request->purchase_return_no;
            $notification->comment = $request->comment;
            $notification->state = "PT Editor";
            $notification->status = 99;
            $notification->save();
        }
        $notification = array(
            'message' => 'Purchase Return Revise Successful!',
            'alert-type' => 'success'
        );
        return redirect('purchase-return-authorize')->with($notification);
    }
    public function purchase_return_revise(){
        $revise_pt = PurchaseReturn::where('status', 99)->get();
        return view('backend.purchase-return.revise-pt-list', compact('revise_pt'));
    }
    public function authorize_pt_rejected($id){
        $authorize_requisition_info = PurchaseReturn::find($id);
        $authorize_requisition_info->status = 100;
        $authorize_requisition_info->state = "PT Authorizer";
        $save = $authorize_requisition_info->save();
        if($save){
            $notification = new Notification;
            $notification->purchase_id = $authorize_requisition_info->purchase_return_no;
            $notification->comment = "PT Rejected from Authorizer";
            $notification->state = "PT Editor";
            $notification->status = 100;
            $notification->save();
        }
        $notification = array(
            'message' => 'Purchase Return Rejected Successful!',
            'alert-type' => 'success'
        );
        return redirect('purchase-return-authorize')->with($notification);
    }
    public function pt_rejected_list_authorize(){
        $pt_rejected_lists = PurchaseReturn::where('status', 100)->where("state", "PT Approval")->get();
        return view("backend.purchase-return.pt-reject-list", compact('pt_rejected_lists'));
    }
    public function pt_rejected_list_editor(){
        $pt_rejected_lists = PurchaseReturn::where('status', 100)->get();
        return view("backend.purchase-return.pt-reject-list", compact('pt_rejected_lists'));
    }
    public function authorize_pt_submit($id){        
        $authorize_requisition_info = PurchaseReturn::find($id);
        $authorize_requisition_info->status = 1;
        $authorize_requisition_info->save();
        $notification = array(
            'message' => 'Purchase Return Authorize Successful!',
            'alert-type' => 'success'
        );
        return redirect('purchase-return-authorize')->with($notification);
    }
    public function purchase_return_approval(){
        $approval_pt = PurchaseReturn::where('status', 1)->get();
        return view('backend.purchase-return.approval-pt-list', compact('approval_pt'));
    }
    public function pt_approval_details(PurchaseReturn $id){
        $pt_info = $id;
        $pt_items = PurchaseReturnDetail::where('purchase_return_no', $pt_info->purchase_return_no)->get();
        return view('backend.purchase-return.pt-approval-details', compact('pt_info', 'pt_items'));
    }
    public function approval_pt_reviece(Request $request){
        $authorize_pt_info = PurchaseReturn::where("purchase_return_no", $request->purchase_return_no)->first();
        $authorize_pt_info->status = 99;
        $authorize_pt_info->state = "PT Approval";
        $save = $authorize_pt_info->save();
        if($save){
            $data = [
                ['purchase_id'=>$request->purchase_return_no, 'comment'=> $request->comment, 'state'=>"PT Editor", 'status'=>99],
                ['purchase_id'=>$request->purchase_return_no, 'comment'=> $request->comment, 'state'=>"PT Authorize", 'status'=>99],
            ];
            Notification::insert($data);
        }
        $notification = array(
            'message' => 'Purchase Return Revise Successful!',
            'alert-type' => 'success'
        );
        return redirect('purchase-return-approval')->with($notification);
    }
    public function revise_pt_authorize_list(){
        $revise_pt = PurchaseReturn::where('status', 99)->where("state", "PT Approval")->get();
        return view('backend.purchase-return.revise-pt-authorise-list', compact('revise_pt'));

    }
    public function approval_pt_rejected(PurchaseReturn $id){
        $approval_pt_info = $id;
        $approval_pt_info->status = 100;
        $approval_pt_info->state = "PT Approval";
        $save = $approval_pt_info->save();
        if($save){
            $data = [
                ['purchase_id'=>$approval_pt_info->purchase_return_no, 'comment'=> "PT Rejected from Approver", 'state'=>"PT Editor", 'status'=>100],
                ['purchase_id'=>$approval_pt_info->purchase_return_no, 'comment'=> "PT Rejected from Approver", 'state'=>"PT Authorize", 'status'=>100],
            ];
            Notification::insert($data);
        }
        $notification = array(
            'message' => 'Purchase Return Rejected Successful!',
            'alert-type' => 'success'
        );
        return redirect('purchase-return-approval')->with($notification);
    }
    public function pt_approval_process(PurchaseReturn $id){

        $approval_pt_info = $id;
        $po_info = Purchase::where("purchase_no", $approval_pt_info->po_no)->first();
        
        $pt_items = PurchaseReturnDetail::where('purchase_return_no', $approval_pt_info->purchase_return_no)->get();
        $approval_pt_info->status = 200;
        $approval_pt_info->save();
        foreach($pt_items as $item){
            $return_qty = $item->return_qty;
            $fifo_po_info = Fifo::orderBy("id", "desc")->where("purchase_id", $po_info->id)->where("item_id", $item->item_id)->get();
            foreach($fifo_po_info as $one_fifo){
                if($return_qty>0){
                    $each_fifo = Fifo::find($one_fifo->id);
                    if($return_qty > $one_fifo->quantity){
                        $each_fifo->quantity = 0;
                    }else{
                        $each_fifo->quantity = $one_fifo->quantity - $return_qty;
                    }
                    if($return_qty > $one_fifo->remaining){
                        $each_fifo->remaining = 0;
                        $return_qty -= $one_fifo->quantity;
                    }else{
                        $each_fifo->remaining = $one_fifo->quantity - $return_qty;
                        $return_qty = 0;
                    }
                    $each_fifo->save();
                }
            }
        }
        foreach($pt_items as $item){
            $stock_effect = new StockTransection;
            $stock_effect->transection_id = $approval_pt_info->id;
            $stock_effect->item_id = $item->item_id;
            $stock_effect->quantity = $item->return_qty;
            $stock_effect->date = $approval_pt_info->date;
            $stock_effect->stock_effect = -1;
            $stock_effect->tns_type_code = "Q";
            $stock_effect->tns_description = "Purchase Return";
            $stock_effect->save();
        }
        $notification = array(
            'message' => 'Purchase Return Transfer Successful!',
            'alert-type' => 'success'
        );
        return redirect('purchase-return-approval')->with($notification);
    }
    public function pt_print(PurchaseReturn $id){
        // dd($id);
        $pt_info = $id;
        $pt_items = PurchaseReturnDetail::where('purchase_return_no', $pt_info->purchase_return_no)->get();
        $return_lists = PurchaseReturn::where("status", 200)->get();
        return view('backend.purchase-return.pt-print-pdf', compact('pt_info', 'pt_items', 'return_lists'));
    }
    public function pt_filter(Request $request){
        if($request->filter_value){
            $filter_value = $request->filter_value;
        }else{
            $filter_value = [];
        }
        $return_lists = PurchaseReturn::orderBy("id", "desc")->whereIn("status", $filter_value)->get();
        return view('backend.purchase-return.filter-value', compact('return_lists'));   
    }
}
