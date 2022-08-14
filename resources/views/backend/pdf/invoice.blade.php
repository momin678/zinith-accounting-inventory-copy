@extends('layouts.pdf.app')
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
@endphp
@push('css')
<style>
    td{
        text-align: center !important;
    }

    .table-bordered th, .table-bordered td {
    border: 1px solid #000 !important;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #000;
}
p{
    color: black !important;
}

@media screen {
  div.divFooter {
    display: none;
  }
}
@media print {
  div.divFooter {
    position: fixed;
    bottom: 0;
  }
}
</style>
@endpush
@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1>{{ $company_name->config_value }}</h1>
                            <h6>{{ $company_address->config_value }}</h6>
                            <div class="row">
                                <div class="col-6 text-right">
                                    <h6>Mobile {{ $company_tele->config_value }}</h6>
                                </div>
                                <div class="col-6 text-left">
                                    <h6>TRN 100305813600003</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center pt-3">
                            <h1>TAX INVOICE</h1>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-left mb-1">
                            <span><strong style="color: #000">CUSTOMER NAME : {{ $invoice->partyInfo($invoice->customer_name)->pi_name }}</strong></span>
                        </div>
                        <div class="col-md-4">
                            <div class="row">

                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>INVOICE NO</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>{{ $invoice->invoice_no }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>SHIP ADDRESS</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->address == null? "NA":$invoice->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>TRN</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->trn_no }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>CONTACT NO:</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->contact_no }}</p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>PAYMODE:</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->pay_mode }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>DATE:</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p> {{ $invoice->date->format('d-m-Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="row pt-2">
                        <table   class="table table-sm table-bordered">
                            <tr>
                                <th class="text-center" colspan="8">Particular Description</th>
                                <th class="text-center"   rowspan="2">Quantity</th>
                                <th class="text-center" rowspan="2">Rate</th>

                                <th class="text-center" rowspan="2" >Amount</th>
                                <th class="text-center"  rowspan="2">VAT</th>
                                <th class="text-center" rowspan="2">Total Amount</th>
                            </tr>

                          @foreach (App\InvoiceItem::where('invoice_id',$invoice->id)->select('style_id')->distinct()
                          ->get() as $it)
                          <tr>
                            <th class="text-center" colspan="8">{{ App\Style::where('id',$it->style_id )->first()->style_name }}</th>
                          </tr>
                          <tr>
                            <td>COLOR</td>
                            <td>Xs</td>
                            <td>S</td>
                            <td>M</td>
                            <td>L</td>
                            <td>XL</td>
                            <td>Xxl</td>
                            <td>Xxxl</td>
                          </tr>
                          @foreach (App\InvoiceItem::where('invoice_id',$invoice->id)->where('style_id',$it->style_id)->select('color_id','style_id','invoice_id','vat_rate')->distinct()
                          ->get() as $color)
                        <tr>
                            <td>{{ App\Brand::where('id',$color->color_id)->first()->name }}</td>
                            <td>{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->where('size','Xs')->sum('quantity') }}</td>
                            <td>{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->where('size','S')->sum('quantity') }}</td>
                            <td>{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->where('size','M')->sum('quantity') }}</td>
                            <td>{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->where('size','L')->sum('quantity') }}</td>
                            <td>{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->where('size','Xl')->sum('quantity') }}</td>
                            <td>{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->where('size','Xxl')->sum('quantity') }}</td>
                            <td>{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->where('size','Xxxl')->sum('quantity') }}</td>

                            <td >{{ $quantity= App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->sum('quantity') }}</td>
                            <td>{{ $costPrice= App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->first()->unit_price }}</td>

                            <td>{{ $totalPrice= number_format((float)( $costPrice*$quantity), 2,'.','')  }}</td>
                            <td>{{ $vat=  App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$color->style_id)->where('color_id',$color->color_id)->sum('vat_amount') }}</td>

                            <td >{{number_format((float)( $totalPrice+$vat), 2,'.','')  }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <th class="text-center" style="border: none !important" colspan="7"></th>
                            <th class="text-center"  colspan="1">Total Item <small>Pcs</small></th>
                            <th class="text-center" colspan="1"  >{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->where('style_id',$it->style_id)->sum('quantity') }}</th>
                        </tr>
                          @endforeach

                          <tr>
                            <th class="text-center" style="border: none !important" colspan="9"></th>
                            <th class="text-center"  colspan="2">Total <small>Pcs</small></th>
                            <th class="text-center" colspan="2"  >{{ App\InvoiceItem::where('invoice_id',$color->invoice_id)->sum('quantity') }}</th>

                        </tr>

                        <tr>
                            <th class="text-center" style="border: none !important" colspan="9"></th>
                            <th class="text-center" colspan="2"  >TAXABLE SUPPLIES</th>
                            <th class="text-center" colspan="2"  >{{number_format((float)( App\InvoiceItem::where('invoice_id',$color->invoice_id)->sum('total_unit_price')), 2,'.','')    }}</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="border: none !important" colspan="9"></th>
                            <th class="text-center" colspan="2"  >VAT</th>
                            <th class="text-center" colspan="2"  > {{number_format((float)(  App\InvoiceItem::where('invoice_id',$color->invoice_id)->sum('vat_amount')), 2,'.','')   }}</th>
                        </tr>

                        <tr>
                            <th class="text-center" style="border: none !important" colspan="9"></th>
                            <th class="text-center" colspan="2"  >Total Amount</th>
                            <th class="text-center" colspan="2"  > {{number_format((float)(App\InvoiceItem::where('invoice_id',$color->invoice_id)->sum('cost_price')), 2,'.','')   }}</th>
                        </tr>

                        </table>

                    </div>

                    <div class="row pt-5 mt-5">

                        <div class="col-6">
                            <div class="row">
                                {{-- <div class="col-12">
                                    <h4>RECEIVED BY</h4>
                                </div> --}}

                                <div class="col-12 pt-5">
                                    <p>Customer Signature</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="row">
                                {{-- <div class="col-12 text-right">
                                    <h4>For {{ $company_name->config_value }}</h4>
                                </div> --}}

                                <div class="col-12 pt-5 text-right">
                                    <p>Authorised Signature</p>
                                    <span>Name: {{ Auth::user()->name }}</span>
                                        <br>
                                    <span class="text-left">User ID: {{ Auth::id() }}</span>
                                </div>
                            </div>
                        </div>

                    </div>


                </section>
            </div>
        </div>
    </div>
    <div class="divFooter">Business softwer solutions by zSprink; product of zinith</div>

@endsection
