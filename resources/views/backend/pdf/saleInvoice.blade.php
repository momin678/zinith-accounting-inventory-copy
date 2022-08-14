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
                            <h1>Sales Order</h1>
                        </div>
                    </div>

                    <div class="row pt-4">
                        <div class="col-12 mb-1">
                            <span><strong>CUSTOMER NAME : {{ $invoice->partyInfo($invoice->customer_name)->pi_name }}</strong></span>

                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>Sale Order NO:</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <p>{{ $invoice->sale_order_no }}</p>
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

                                <th>Total Price</th>
                            </tr>

                            @foreach ($invoice->items($invoice->sale_order_no) as $item)
                            {{-- {{ dd($item) }} --}}
                            <tr>
                                <td>{{ $item->barcode }}</td>
                                <td>{{ $item->item->item_name }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{number_format((float)( $item->cost_price/$item->quantity), 3,'.','')  }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td> {{number_format((float)( $item->cost_price ), 2,'.','')}}</td>
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
                                        {{number_format((float)( $invoice->grossTotal($invoice->sale_order_no) ), 2,'.','')  }}
                                    </div>
                                    {{-- <div class="col-9 text-right">
                                        <strong>VAT:</strong>
                                    </div> --}}
                                    {{-- <div class="col-3">
                                        {{ $invoice->vat($invoice->sale_order_no) }}
                                    </div>

                                    <div class="col-9 text-right">
                                        <strong>GROSS TOTAL:</strong>
                                    </div>
                                    <div class="col-3">
                                        {{ $invoice->grossTotal($invoice->sale_order_no) }}
                                    </div> --}}

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
