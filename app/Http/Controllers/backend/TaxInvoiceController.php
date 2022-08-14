<?php

namespace App\Http\Controllers\backend;

use App\Branch;
use App\CostCenterType;
use App\DeliveryItem;
use App\DeliveryNote;
use App\DeliveryNoteSale;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceAmount;
use App\InvoiceTemp;
use App\InvoiceItem;
use App\InvoiceItemTemp;
use App\ItemList;
use App\Mapping;
use App\PartyInfo;

use App\PayMode;
use App\PayTerm;
use App\ProjectDetail;
use App\SaleInvoice;
use App\SaleOrder;
use App\SaleOrderItem;
use App\Stock;
use App\StockTransection;
use App\TempInvoice;
use Carbon\Carbon;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;

class TaxInvoiceController extends Controller
{
    public function taxInvoIssue()
    {
        $delete_invoice_temp=InvoiceTemp::whereDate('created_at','<', Carbon::today())->delete();
        // dd($delete_item);
        $sub_invoice=Carbon::now()->format('Ymd');
        $latest_invoice_no=InvoiceTemp::whereDate('created_at', Carbon::today())->where('invoice_no','LIKE',"%{$sub_invoice}%")->latest()->first();
        // dd($latest_invoice_no);
        if($latest_invoice_no)
        {
            $invoice_no=$latest_invoice_no->invoice_no+1;
        }
        else
        {
            $invoice_no=Carbon::now()->format('Ymd').'001';
        }
        // dd(Carbon::now()->format('Ymd'));
        $invoice=new InvoiceTemp;
        $invoice->invoice_no=$invoice_no;
        $invoice->save();
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $branches=Branch::get();
        $customers=PartyInfo::get();
        $invoicess=Invoice::latest()->paginate(25);
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $gl_code=Mapping::where('fld_txn_type',"sale")->first();

        $latest = PartyInfo::withTrashed()->latest()->first();

        if ($latest) {
            $pi_code=preg_replace('/^PI-/', '', $latest->pi_code );
            ++$pi_code;
        } else {
            $pi_code = 1;
        }
        if($pi_code<10)
        {
            $cc="PI-000".$pi_code;
        }
        elseif($pi_code<100)
        {
            $cc="PI-00".$pi_code;
        }
        elseif($pi_code<1000)
        {
            $cc="PI-0".$pi_code;
        }
        else
        {
            $cc="PI-".$pi_code;
        }
        $costTypes=CostCenterType::get();
            return view('backend.taxInvoice.taxtInvoiceIssue',compact('costTypes','cc','customers','modes','terms','branches','customers','invoice','invoicess','projects','itms','gl_code'));
    }



    public function selectItemByTerm(Request $request)
    {

        $item=PartyInfo::where('cc_code',$request->value)->first();
        // return $party;
        return $item;


    }

    public function partyInfoInvoice(Request $request)
    {
        // return $request->all();
        $info=PartyInfo::where('pi_code',$request->value)->first();
        return $info;
    }

    public function findDate(Request $request)
    {
        $date=Carbon::today()->addDays((int)$request->value)->format('Y-m-d');
        return $date;
    }


    public function findItem(Request $request)
    {
        $item=ItemList::where('barcode',$request->value)->first();
        $vat=100-(int)$item->vatRate->value;
        $unit_price=($item->sell_price/100)*$vat;

        if ($request->ajax()) {
            return Response()->json([   'item' => $item,
                                        'unit_price' => $unit_price,
                                        'net_amount' =>number_format((float)($item->total_amount),2,'.',''),
        ]);
        }
    }


    public function findItemId(Request $request)
    {
        $item=ItemList::where('id',$request->value)->first();
        $vat=100-(int)$item->vatRate->value;
        $unit_price=($item->sell_price/100)*$vat;
        // return $item->total_amount;
        if ($request->ajax()) {
            return Response()->json([   'item' => $item,
                                        'unit_price' => $item->total_amount,
                                        'net_amount' => number_format((float)($item->total_amount),2,'.',''),
                                        'cost_price' => $item->total_amount
        ]);
        }

    }

    public function tempInvoice(Request $request)
    {

        if(!$request->branch)
        {
            if ($request->ajax()) {
                return Response()->json(['fail' => 'Branch is required']);
            }
        }
        $project=ProjectDetail::where('id',$request->branch)->first();
        $itm=ItemList::where('id',$request->item_name)->first();
        $temp=InvoiceItemTemp::where('invoice_no',$request->invoice_no)->where('item_id',$itm->id)->first();
        $checkStock= $project->stockCheck($project->id,$itm->id);
        $vat=100-(int)$itm->vatRate->value;
        if(!$temp)
        {
        if($checkStock < $request->quantity)
        {
            if ($request->ajax()) {
                return Response()->json(['stockout' => 'Stock Out']);
            }
        }

        $temp=new InvoiceItemTemp();
        $temp->invoice_no=$request->invoice_no;
        $temp->barcode=$itm->barcode;
        $temp->item_id=$itm->id;
        $temp->style_id=$itm->style_id;
        $temp->size=$itm->group_name;
        $temp->color_id=$itm->brand_id;
        $temp->quantity=$request->quantity;
        $temp->vat_rate=$itm->vatRate->value;
        $temp->unit=$itm->unit;
        $temp->cost_price=$itm->total_amount*$temp->quantity;
        $temp->unit_price=$itm->sell_price;
        $temp->net_amount=$temp->unit_price*$temp->quantity;
        $temp->total_unit_price=$temp->unit_price*$temp->quantity;
        $temp->vat_amount= $temp->cost_price-($temp->unit_price*$temp->quantity);
        }
        else
        {
            if($checkStock < ($temp->quantity+$request->quantity))
            {
                if ($request->ajax()) {
                    return Response()->json(['stockout' => 'Not in stock']);
                }
            }
            $temp->quantity=$temp->quantity+$request->quantity;
            $temp->cost_price=$itm->total_amount*$temp->quantity;
            $temp->unit_price=$itm->sell_price;
            $temp->net_amount=$temp->unit_price*$temp->quantity;
            $temp->total_unit_price=$temp->unit_price*$temp->quantity;
            $temp->vat_amount= $temp->cost_price-($temp->unit_price*$temp->quantity);
        }
        $temp->save();

        $total_cost_price=InvoiceItemTemp::where('invoice_no',$request->invoice_no)->sum('cost_price');
        $total_unit_price=InvoiceItemTemp::where('invoice_no',$request->invoice_no)->sum('total_unit_price');
        $total_vat_amount=InvoiceItemTemp::where('invoice_no',$request->invoice_no)->sum('vat_amount');
        $invoice_draft=InvoiceTemp::where('invoice_no',$request->invoice_no)->latest()->first();
        if ($request->ajax()) {

            if($total_cost_price<10000)
           {
            return Response()->json(['page' => view('backend.ajax.invoice', ['invoice_draft' => $invoice_draft,'i' =>1])->render(),
            'total_cost_price' =>number_format((float)($total_cost_price),2,'.','') ,
            'total_unit_price' =>number_format((float)($total_unit_price),2,'.','') ,
            'total_vat_amount' =>number_format((float)($total_vat_amount),2,'.','')
]);
           }
           else
           {
            return Response()->json(['page' => view('backend.ajax.invoice2', ['invoice_draft' => $invoice_draft,'i' =>1])->render(),
            'total_cost_price' => number_format((float)($total_cost_price),2,'.','') ,
            'total_unit_price' => number_format((float)($total_unit_price),2,'.','') ,
            'total_vat_amount' => number_format((float)($total_vat_amount),2,'.','')
            ]);
           }
        }
    }


    public function finalSaveInvoice(Request $request)
    {
        // dd($request->all());
        $gl_code=Mapping::where('fld_txn_type',"sale")->first();

        $invoice=Invoice::where('invoice_no',$request->invoice_no)->first();
        if(!$invoice)
        {
            $invoice=new Invoice;
            $invoice->invoice_no=$request->invoice_no;
        }
        else
        {
            InvoiceItem::where('invoice_no',$request->invoice_no)->delete();
        }
        $invoice->date=$request->date;
        $invoice->project_id=$request->branch;
        $invoice->customer_name=$request->customer_name;
        $invoice->trn_no=$request->trn_no;
        $invoice->pay_mode=$request->pay_mode;
        $invoice->pay_terms=$request->pay_terms;
        $invoice->due_date=$request->due_date;
        $invoice->contact_no=$request->contact_no;
        $invoice->address=$request->address;
        $invoice->gl_code=$gl_code ? $gl_code->fld_ac_code: null;
        $invoice->save();
        $items=InvoiceItemTemp::where('invoice_no',$request->invoice_no)->get();
        foreach($items as $item)
        {
            $invoice_item=new InvoiceItem;
            $invoice_item->invoice_no=$item->invoice_no;
            $invoice_item->invoice_id=$invoice->id;
            $invoice_item->barcode=$item->barcode;
            $invoice_item->item_id=$item->item_id;
            $invoice_item->style_id=$item->style_id;
            $invoice_item->size=$item->size;
            $invoice_item->color_id=$item->color_id;
            $invoice_item->net_amount=1;
            $invoice_item->quantity=$item->quantity;
            $invoice_item->vat_rate=$item->vat_rate;
            $invoice_item->vat_amount=$item->vat_amount;
            $invoice_item->unit=$item->unit;
            $invoice_item->total_unit_price=$item->total_unit_price;
            $invoice_item->cost_price=$item->cost_price;
            $invoice_item->unit_price=$item->unit_price;
            $invoice_item->save();
            $stock=StockTransection::where('transection_id',$invoice->id)->where('tns_type_code','s')->where('item_id',$invoice_item->id)->first();
            $latestStock=StockTransection::latest()->first();
            if(!$stock)
            {
                $stock=new StockTransection();
                $stock->transection_id=$invoice->id;
                $stock->item_id=$item->item_id;
            }
            $stock->quantity=$item->quantity;
            $stock->stock_effect = -1 ;
            $stock->tns_type_code="S";
            $stock->tns_description="Sales";
            $stock->cost_price=$item->cost_price;
            $stock->save();
        }

        if($request->amount_from != null)
        {
            $invoiceAmount=new InvoiceAmount;
        $invoiceAmount->invoice_id=$invoice->id;
        $invoiceAmount->amount_from=$request->amount_from;
        $invoiceAmount->amount_to=$request->amount_from - number_format((float)( $invoice->grossTotal($invoice->invoice_no)),'2','.','');
        $invoiceAmount->save();
        }
        return redirect()->route('invoiceView',$invoice)->with('success', 'Succesfully Generated');
    }


    public function invoicess()
    {
        $invoicess=Invoice::latest()->get();
        return view('backend.taxInvoice.invoicess',compact('invoicess'));
    }

    public function invoicePrint($invoice)
    {
        $invoice=Invoice::where('id',$invoice)->first();
        if($invoice->taxbleSup($invoice->invoice_no)>9999)
        {
            return view('backend.pdf.invoice',compact('invoice'));
        }
        else
        {
            return view('backend.pdf.invoice2',compact('invoice'));
        }
    }


    public function itemDelete($item, Request $request)
    {
        $itm=InvoiceItemTemp::where('id',$item)->first();
        $invoice_no=$itm->invoice_no;
        $itm->delete();
        $total_cost_price=InvoiceItemTemp::where('invoice_no',$invoice_no)->sum('cost_price');
        $total_unit_price=InvoiceItemTemp::where('invoice_no',$invoice_no)->sum('total_unit_price');
        $total_vat_amount=InvoiceItemTemp::where('invoice_no',$invoice_no)->sum('vat_amount');
        $invoice_draft=InvoiceTemp::where('invoice_no',$invoice_no)->latest()->first();
        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.invoice', ['invoice_draft' => $invoice_draft,'i' =>1])->render(),
                                        'total_cost_price' => $total_cost_price,
                                        'total_unit_price' => $total_unit_price,
                                        'total_vat_amount' => $total_vat_amount
        ]);
        }
    }


    public function refresh_invoice(Request $request)
    {
        $invoicess=Invoice::latest()->paginate(25);
        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.invoiceRight', ['invoicess' => $invoicess,'i' =>1])->render()
        ]);
        }
    }

    public function invoiceView($invoice)
    {
        $invoice=Invoice::where('id',$invoice)->first();
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $branches=Branch::get();
        $customers=PartyInfo::get();
        $invoicess=Invoice::latest()->paginate(25);
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $i=0;
        return view('backend.taxInvoice.invoiceView',compact('invoice','modes','terms','branches','customers','invoicess','projects','itms', 'i'));
    }

    public function invoiceView2($invoice)
    {
        $invoice=Invoice::where('id',$invoice)->first();
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $branches=Branch::get();
        $customers=PartyInfo::get();
        $invoicess=Invoice::latest()->paginate(25);
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $i=0;
        return view('backend.taxInvoice.invoiceView2',compact('invoice','modes','terms','branches','customers','invoicess','projects','itms', 'i'));
    }



    public function invoiceEdit($invoice)
    {
        $invoice=Invoice::where('id',$invoice)->first();
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $branches=Branch::get();
        $customers=PartyInfo::get();
        $invoicess=Invoice::latest()->paginate(25);
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $i=0;
        $invoice_temp=InvoiceTemp::where('invoice_no',$invoice->invoice_no)->first();
        if(!$invoice_temp)
        {
            $invoice_temp=new InvoiceTemp;
            $invoice_temp->invoice_no=$invoice->invoice_no;
            $invoice_temp->save();
        }
        InvoiceItemTemp::where('invoice_no',$invoice->invoice_no)->delete();
        $items=InvoiceItem::where('invoice_no',$invoice->invoice_no)->get();
        foreach($items as $item)
        {
            $item_temp=new InvoiceItemTemp;
            $item_temp->invoice_no=$item->invoice_no;
            $item_temp->barcode=$item->barcode;
            $item_temp->item_id=$item->item_id;
            $item_temp->style_id=$item->style_id;
            $item_temp->size=$item->size;
            $item_temp->color_id=$item->color_id;
            $item_temp->net_amount=1;
            $item_temp->quantity=$item->quantity;
            $item_temp->vat_rate=$item->vat_rate;
            $item_temp->vat_amount=$item->vat_amount;
            $item_temp->unit=$item->unit;
            $item_temp->total_unit_price=$item->total_unit_price;
            $item_temp->cost_price=$item->cost_price;
            $item_temp->unit_price=$item->unit_price;
            $item_temp->save();
        }
        return view('backend.taxInvoice.invoiceEdit',compact('invoice','modes','terms','branches','customers','invoicess','projects','itms', 'i'));
    }


    public function finalSaveInvoiceUpdate(Request $request, $invoice)
    {
        $invoice=Invoice::where('id',$invoice)->first();
        if(!$invoice)
        {
            $invoice=new Invoice;
            $invoice->invoice_no=$request->invoice_no;
        }
        // dd($request->customer_name);
        $invoice->date=$request->date;
        $invoice->project_id=$request->branch;
        $invoice->customer_name=$request->customer_name;
        $invoice->trn_no=$request->trn_no;
        $invoice->pay_mode=$request->pay_mode;
        $invoice->pay_terms=$request->pay_terms;
        $invoice->due_date=$request->due_date;
        $invoice->contact_no=$request->contact_no;
        $invoice->address=$request->address;
        $invoice->save();

        InvoiceItem::where('invoice_no',$invoice->invoice_no)->forceDelete();
        $items=InvoiceItemTemp::where('invoice_no',$invoice->invoice_no)->get();
        foreach($items as $item)
        {
            $invoice_item=new InvoiceItem;
            $invoice_item->invoice_no=$item->invoice_no;
            $invoice_item->invoice_id=$invoice->id;
            $invoice_item->barcode=$item->barcode;
            $invoice_item->item_id=$item->item_id;
            $invoice_item->style_id=$item->style_id;
            $invoice_item->size=$item->size;
            $invoice_item->color_id=$item->color_id;
            $invoice_item->net_amount=1;
            $invoice_item->quantity=$item->quantity;
            $invoice_item->vat_rate=$item->vat_rate;
            $invoice_item->vat_amount=$item->vat_amount;
            $invoice_item->unit=$item->unit;
            $invoice_item->total_unit_price=$item->total_unit_price;
            $invoice_item->cost_price=$item->cost_price;
            $invoice_item->unit_price=$item->unit_price;
            $invoice_item->save();
            $stock=StockTransection::where('transection_id',$invoice->id)->where('item_id',$item->item_id)->first();
            if(!$stock)
            {
                $stock=new StockTransection();
                $stock->transection_id=$invoice->id;
                $stock->item_id=$item->item_id;
            }
            $stock->quantity=$item->quantity;
            $stock->stock_effect = -1 ;
            $stock->tns_type_code="S";
            $stock->tns_description="Sales";
            $stock->cost_price=$item->cost_price;

            $stock->save();
        }
        return redirect()->route('invoicePrint',$invoice);
    }


    public function salesTaxtInvoiceIssue()
    {
        $dNotes=DeliveryNote::whereDoesntHave('DInvoice')->latest()->paginate(60);
        return view('backend.taxInvoice.salesTaxtInvoiceIssue', compact('dNotes'));
    }


    public function saleOrderTaxInvoice($sale, Request $request)
    {
        $invoice=SaleOrder::find($sale);
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $customers=PartyInfo::get();
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $notes=DeliveryNote::all();
        $i=0;
        if(!$invoice)
        {
            return back()->with('error', "Not Found");
        }

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.saleRaxInvoiceDetails', ['invoice' => $invoice,'i' =>1, 'modes'=>$modes, 'terms'=> $terms, 'customers'=>$customers,'projects'=>$projects,'itms'=>$itms,'notes'=>$notes])->render(),
                                        'sale'=>$sale
        ]);
        }

    }


    public function deliveryNotInvoice($dnote, Request $request)
    {

        $deliveryNote=DeliveryNote::find($dnote);
        // return $deliveryNote;
        $saleOrder=$deliveryNote->deliverySale->saleOrder;
        // return $saleOrder;
        $modes=PayMode::get();
        $terms=PayTerm::get();
        $customers=PartyInfo::get();
        $projects=ProjectDetail::get();
        $itms=ItemList::get();
        $notes=DeliveryNote::all();
        $i=0;
        if(!$saleOrder)
        {
            return back()->with('error', "Not Found");
        }

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.saleRaxInvoiceDetails', ['invoice' => $saleOrder,'i' =>1, 'modes'=>$modes, 'terms'=> $terms, 'customers'=>$customers,'projects'=>$projects,'itms'=>$itms,'notes'=>$notes, 'dnote' =>$dnote])->render(),
                                        'dnote'=>$dnote
        ]);
        }

    }


    public function genTaxInvoiceSO($sale)
    {
        $saleOrder=SaleOrder::where('id',$sale)->first();
        if(!$saleOrder)
        {
            return back()->with('error',"Not Found");
        }
        $invoiceNew=Invoice::where('sale_order_id',$saleOrder->id)->first();
        if(!$invoiceNew)
        {
            $sub_invoice=Carbon::now()->format('Ymd');
            $latest_invoice_no=InvoiceTemp::whereDate('created_at', Carbon::today())->where('invoice_no','LIKE',"%{$sub_invoice}%")->latest()->first();
            if($latest_invoice_no)
            {
                $invoice_no=$latest_invoice_no->invoice_no+1;
            }
            else
            {
                $invoice_no=Carbon::now()->format('Ymd').'001';
            }
            $invoicetemp=new InvoiceTemp;
            $invoicetemp->invoice_no=$invoice_no;
            $invoicetemp->save();
            $invoiceNew=new Invoice();
            $invoiceNew->invoice_no= $invoicetemp->invoice_no;
            $invoiceNew->sale_order_id=$saleOrder->id;
            $invoiceNew->date=$saleOrder->date;
            $invoiceNew->project_id=$saleOrder->project_id;
            $invoiceNew->customer_name=$saleOrder->customer_name;
            $invoiceNew->trn_no= $saleOrder->trn_no;
            $invoiceNew->pay_mode=$saleOrder->pay_mode;
            $invoiceNew->pay_terms=$saleOrder->pay_terms;
            $invoiceNew->due_date=$saleOrder->due_date;
            $invoiceNew->contact_no=$saleOrder->contact_no;
            $invoiceNew->address=$saleOrder->address;
            $invoiceNew->gl_code=$saleOrder->gl_code;
            $invoiceNew->save();
            $items=SaleOrderItem::where('sale_order_id',$saleOrder->id)->get();
            foreach($items as $item)
            {
                $invoice_item=new InvoiceItem();
                $invoice_item->invoice_no=$invoiceNew->invoice_no;
                $invoice_item->invoice_id=$invoiceNew->id;
                $invoice_item->barcode=$item->barcode;
                $invoice_item->item_id=$item->item_id;
                $invoice_item->net_amount=1;
                $invoice_item->quantity=$item->quantity;
                $invoice_item->vat_rate=$item->vat_rate;
                $invoice_item->vat_amount=$item->vat_amount;
                $invoice_item->unit=$item->unit;
                $invoice_item->total_unit_price=$item->total_unit_price;
                $invoice_item->cost_price=$item->cost_price;
                $invoice_item->unit_price=$item->unit_price;
                $invoice_item->save();
                $stock=StockTransection::where('transection_id',$invoiceNew->id)->where('item_id',$invoice_item->id)->where('tns_type_code',"S")->first();
                $latestStock=StockTransection::latest()->first();
                if(!$stock)
                {
                    $stock=new StockTransection();
                    $stock->transection_id=$invoiceNew->id;
                    $stock->item_id=$item->item_id;
                }
                $stock->quantity=$item->quantity;
                $stock->stock_effect = -1 ;
                $stock->tns_type_code="S";
                $stock->tns_description="Sales";
                $stock->save();
            }
            $saleInvoicenew=new SaleInvoice();
            $saleInvoicenew->sale_order_id= $saleOrder->id;
            $saleInvoicenew->invoice_id= $invoiceNew->id;
            $saleInvoicenew->save();
            return redirect()->route('invoiceView',$invoiceNew)->with('success', "Genrated Successfully");
        }
        return redirect()->route('invoiceView',$invoiceNew)->with('error', "Already Generated. You can see the invoice now.");
    }

    public function taxInvoiceList()
    {
        $invoicess=Invoice::latest()->paginate(100);
        return view('backend.taxInvoice.invoiceList', compact('invoicess'));
    }



    public function genTaxInvoiceDN($dnote)
    {
        $deliveryNote=DeliveryNote::where('id',$dnote)->first();
        if(!$deliveryNote)
        {
            return back()->with('error',"Not Found");
        }
        $saleOrder=$deliveryNote->deliverySale->saleOrder;
        $invoiceNew=Invoice::where('sale_order_id',$saleOrder->id)->where('delivery_note_id',$deliveryNote->id)->first();
        if(!$invoiceNew)
        {
            $sub_invoice=Carbon::now()->format('Ymd');
            $latest_invoice_no=InvoiceTemp::whereDate('created_at', Carbon::today())->where('invoice_no','LIKE',"%{$sub_invoice}%")->latest()->first();
            if($latest_invoice_no)
            {
                $invoice_no=$latest_invoice_no->invoice_no+1;
            }
            else
            {
                $invoice_no=Carbon::now()->format('Ymd').'001';
            }
            $invoicetemp=new InvoiceTemp;
            $invoicetemp->invoice_no=$invoice_no;
            $invoicetemp->save();
            $invoiceNew=new Invoice();
            $invoiceNew->invoice_no= $invoicetemp->invoice_no;
            $invoiceNew->delivery_note_id= $deliveryNote->id;
            $invoiceNew->sale_order_id=$saleOrder->id;
            $invoiceNew->date=$saleOrder->date;
            $invoiceNew->project_id=$saleOrder->project_id;
            $invoiceNew->customer_name=$saleOrder->customer_name;
            $invoiceNew->trn_no= $saleOrder->trn_no;
            $invoiceNew->pay_mode=$saleOrder->pay_mode;
            $invoiceNew->pay_terms=$saleOrder->pay_terms;
            $invoiceNew->due_date=$saleOrder->due_date;
            $invoiceNew->contact_no=$saleOrder->contact_no;
            $invoiceNew->address=$saleOrder->address;
            $invoiceNew->gl_code=$saleOrder->gl_code;
            $invoiceNew->save();
            $items=DeliveryItem::where('sale_order_id',$saleOrder->id)->where('delivery_note_id',$deliveryNote->id)->get();
            foreach($items as $item)
            {
                // return $item->saleItem;
                $invoice_item=new InvoiceItem();
                $invoice_item->invoice_no=$invoiceNew->invoice_no;
                $invoice_item->invoice_id=$invoiceNew->id;
                $invoice_item->barcode=$item->saleItem->barcode;
                $invoice_item->item_id=$item->saleItem->item_id;
                $invoice_item->style_id=$item->style_id;
                $invoice_item->size=$item->size;
                $invoice_item->color_id=$item->color_id;
                $invoice_item->net_amount=1;
                $invoice_item->quantity=$item->quantity;
                $invoice_item->vat_rate=$item->saleItem->vat_rate;
                $invoice_item->unit=$item->saleItem->unit;
                $invoice_item->total_unit_price=$item->saleItem->total_unit_price/$item->saleItem->quantity*$item->quantity;
                $invoice_item->cost_price=$item->saleItem->cost_price/$item->saleItem->quantity*$item->quantity;
                $invoice_item->unit_price=$item->saleItem->unit_price;
                $invoice_item->vat_amount= $invoice_item->total_unit_price *($item->saleItem->vat_rate/100);

                $invoice_item->save();
                $stock=StockTransection::where('transection_id',$invoiceNew->id)->where('item_id',$invoice_item->id)->where('tns_type_code',"S")->first();
                $latestStock=StockTransection::latest()->first();
                if(!$stock)
                {
                    $stock=new StockTransection();
                    $stock->transection_id=$invoiceNew->id;
                    $stock->item_id=$item->saleItem->item_id;
                }
                $stock->quantity=$item->quantity;
                $stock->stock_effect = -1 ;
                $stock->tns_type_code="S";
                $stock->tns_description="Sales";
                $stock->cost_price=$invoice_item->cost_price;
                $stock->save();
            }
            // $saleInvoicenew=new SaleInvoice();
            // $saleInvoicenew->sale_order_id= $saleOrder->id;
            // $saleInvoicenew->invoice_id= $invoiceNew->id;
            // $saleInvoicenew->save();
            return redirect()->route('invoiceView',$invoiceNew)->with('success', "Genrated Successfully");
        }
        return redirect()->route('invoiceView',$invoiceNew)->with('error', "Already Generated. You can see the invoice now.");
    }


    public function searchDNo(Request $request)
    {
        $dNotes=DeliveryNote::where('delivery_note_no', 'LIKE', "%{$request->value}%")->get();
        // $dNotes=DeliveryNoteSale::where('delivery_note_no', 'LIKE', "%{$request->value}%")->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotSearch', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }

    public function searchDNoMonth(Request $request)
    {
        $year=substr($request->value, 0, 4);
        $month=substr($request->value, 5, 8);
        $dNotes=DeliveryNote::whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotSearch', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }

    public function searchDNoDate(Request $request)
    {

        $dNotes=DeliveryNote::whereDate('created_at', $request->value)->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotSearch', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }

    public function searchDNoDateRange(Request $request)
    {

        $dNotes=DeliveryNote::whereBetween('created_at', [$request->from, $request->to])->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.DeliveryNotSearch', ['dNotes' => $dNotes,'i' =>1])->render()
        ]);
        }
    }


    public function searchInvoice(Request $request)
    {
        // return $request->all();
        $invoicess=Invoice::where('invoice_no', 'LIKE', "%{$request->value}%")->get();

        // $invoicess=Invoice::latest()->paginate(25);

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.invoiceRight', ['invoicess' => $invoicess,'i' =>1])->render()
        ]);
        }
    }

    public function amountto(Request $request)
    {
        $invoice=Invoice::where('invoice_no', $request->invoice)->first();
        // return $invoice->invoiceAmount;
        $invoiceAmount=$invoice->invoiceAmount;
        // dd($invoiceAmount);
        if(!$invoiceAmount)
        {
            $invoiceAmount=new InvoiceAmount;
        }
        $invoiceAmount->invoice_id=$invoice->id;
        $invoiceAmount->amount_from=$request->value;
        $invoiceAmount->amount_to=$request->value-$request->c;
        $invoiceAmount->save();
        $amount_to=number_format((float)($invoiceAmount->amount_to),'2','.','');
        if ($request->ajax()) {
            return Response()->json(['amount_to'=>$amount_to]);
        }
    }


}
