@extends('layouts.pdf.app')
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
@endphp
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
                            <h1>SALES RETURN</h1>
                        </div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>INVOICE NO:</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->invoice_no }} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>SHIP ADDRESS:</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->address == null? "NA":$invoice->address }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>TRN:</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->trn_no }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>CONTACT NO:</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            {{ $invoice->contact_no }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p> <strong>TO:</strong> </p>
                                        </div>
                                        <div class="col-6">
                                            <p>CASH CUSTOMER</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-4">
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
                                            <p> {{ $invoice->date }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <table id="customers">
                            <tr>
                                <th>ITEM NO</th>
                                <th>PRODUCT NAME</th>
                                <th>UNIT</th>
                                <th>UNIT PRICE</th>
                                <th>QUANTITY</th>
                                <th>NET AMOUNT</th>
                                {{-- <th>COST PRICE</th> --}}
                            </tr>

                            @foreach (App\SaleReturn::where('invoice_no', $invoice->invoice_no)->get() as $item)
                            {{-- {{ dd($item) }} --}}
                            <tr>
                                <td>{{ $item->barcode }}</td>
                                <td>{{ $item->item->item_name }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ $item->unit_price }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_price*$item->quantity}}</td>
                                <td> {{-- {{ $item->cost_price }} --}}</td>
                            </tr>

                            @endforeach



                        </table>
                    </div>

                    <div class="row d-flex justify-content-end pt-4">
                        <div class="col-4">
                            <div class="row d-flex justify-content-end">
                                    <div class="col-9 text-right">
                                        <strong>TAXBLE SUPPLIES:</strong>
                                    </div>
                                    <div class="col-3">
                                        {{-- {{ $invoice->taxbleSup($invoice->invoice_no) }} --}}
                                    </div>
                                    <div class="col-9 text-right">
                                        <strong>VAT:</strong>
                                    </div>
                                    <div class="col-3">
                                        {{-- {{ $invoice->vat($invoice->invoice_no) }} --}}
                                    </div>

                                    <div class="col-9 text-right">
                                        <strong>GROSS TOTAL:</strong>
                                    </div>
                                    <div class="col-3">
                                        {{-- {{ $invoice->grossTotal($invoice->invoice_no) }} --}}
                                    </div>

                            </div>

                        </div>
                    </div>

                    <div class="row pt-5 mt-5">

                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <h4>RECEIVED BY</h4>
                                </div>

                                <div class="col-12 pt-5">
                                    <h4>SIGNATURE</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="row">
                                <div class="col-12 text-right">
                                    <h4>For {{ $company_name->config_value }}</h4>
                                </div>

                                <div class="col-12 pt-5 text-right">
                                    <h4>AUTHOROZED SIGNATORY</h4>
                                </div>
                            </div>
                        </div>

                    </div>


                </section>
            </div>
        </div>
    </div>
@endsection
