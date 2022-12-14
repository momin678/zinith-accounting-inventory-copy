@extends('layouts.backend.app')
@push('css')
<style>
    .bx-filter{
        font-size: 30px;
        line-height: 0px;
    }
</style>
@endpush
@section('title', 'item-purchase')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="row" id="table-bordered">
                    <div class="col-12 col-sm-10 col-md-10 col-lg-10">
                        <form action="{{route('purchase-temp.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <h5>Product Purchase Order</h5>
                                <div class="card mb-1">
                                    <div class="card-body content-padding">
                                        <div class="row d-flex align-items-center">
                                            <div class="col-sm-2 col-12">
                                                <label for="mode">PO No</label>
                                                @php
                                                    $temp_po_no = '';
                                                    if($new_po_no){
                                                        $temp_po_no = $new_po_no->po_no+1;
                                                    }else {
                                                        $temp_po_no = date("Ymd").'01';
                                                    }
                                                    $po_no = $temp_po_no."PO"
                                                @endphp
                                                <input type="text" required value="" readonly class="form-control">
                                                <input type="hidden" required value="{{$po_no}}" readonly class="form-control" name="purchase_no" id="purchase_no">
                                                @error('purchase_no')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                                <span class="text-danger" id="purchaseNoErrorMsg"></span>
                                                <input type="hidden" value="{{$temp_po_no}}" name="temp_purchase_no" id="temp_purchase_no">
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="pr_id">Approved PR List</label>
                                                <select name="pr_id" id="pr_id" class="form-control" required disabled>
                                                    <option value=""></option>
                                                    @foreach ($pr_lists as $pr_list)
                                                        <option value="{{$pr_list->id}}" {{old('pr_id') == $pr_list->id ? "selected": ""}}>{{$pr_list->purchase_no}}</option>
                                                    @endforeach
                                                </select>
                                                @error('pr_id')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="project_id">Branch Name</label>
                                                <input type="hidden" name="project_id" id="project_id" value="{{old('project_id')}}" required>
                                                <input type="text" class="form-control" id="project_name" value="{{old('project_name')}}" name="project_name" readonly>
                                                @error('project_id')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3 col-12 customer-select">
                                                <label for="mode">Supplier Name</label>
                                                <select name="supplier_id" id="supplier_id" required class="form-control common-select2 customer" disabled >
                                                    <option value=""></option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{$supplier->id}}" {{old('supplier_id') == $supplier->id ? "selected": ""}}>{{$supplier->pi_name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('supplier_id')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-1 pt-1 pr-1" style="padding-left: 0">
                                                <i class="bx bx-user-plus btn btn-info btn-sm text-center" data-toggle="modal" data-target="#customerModal"></i>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="contact_no">Contact No</label>
                                                <input type="text" required class="form-control" name="contact_no" id="contact_no" value="{{old('contact_no')}}" readonly>
                                                @error('contact_no')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="address">Address</label>
                                                <input type="text" name="address" class="form-control" id="address" readonly value="{{old('address')}}">
                                                @error('address')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="trn">TRN</label>
                                                <input type="text" name="trn" class="form-control" id="trn" readonly value="{{old('trn')}}">
                                                @error('trn')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="tax_invoice_no">Quotation / Reference No</label>
                                                <input type="text" required class="form-control" name="tax_invoice_no" id="tax_invoice_no" value="{{old('tax_invoice_no')}}" readonly>
                                                @error('tax_invoice_no')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="pay_mode">Payment Mode</label>
                                                <select name="pay_mode" id="pay_mode" class="form-control" required disabled>
                                                    <option value=""></option>
                                                    @foreach ($payMode as $payMode)
                                                        <option value="{{$payMode->title}}" {{ old('pay_mode') == $payMode->title ? "selected" : "" }}>{{$payMode->title}}</option>                                                    
                                                    @endforeach
                                                    @error('pay_mode')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </select>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="pay_term">Payment Terms</label>
                                                <select name="pay_term" id="pay_term" class="form-control" required disabled>
                                                    <option value=""></option>
                                                    @foreach ($payTerms as $payTerm)
                                                        <option value="{{$payTerm->value}}" {{ old('pay_term') == $payTerm->value ? "selected" : "" }}>{{$payTerm->title}}</option>
                                                    @endforeach
                                                    @error('pay_term')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </select>
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="pay_date">Payment Date</label>
                                                <input type="date" name="pay_date" class="form-control" id="pay_date" readonly>
                                                <span class="text-danger" id="payDateErrorMsg"></span>
                                                @error('pay_date')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="shipping_id">Shipment</label>
                                                <input type="text" class="form-control" name="shipping_id" id="shipping_id" value="{{old('shipping_id')}}" required readonly>
                                                @error('shipping_id')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-3 col-12">
                                                <label for="date">PO Date</label>
                                                <input type="date" class="form-control" name="date" id="date" value="{{old('date')}}" required readonly>
                                                @error('date')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-1">
                                <div class="card-body content-padding">
                                    <div class="row">
                                        <div class="col-sm-6 col-12">
                                            <label for="mode">Item Name</label>
                                            <select name="item_list_id" id="item_list_id" class="form-control" disabled>
                                                
                                            </select>
                                            <span class="text-danger" id="itemListErrorMsg"></span>
                                        </div>
                                        <div class="col-sm-3 col-12">
                                            <label for="quantity">QTY</label>
                                            <input type="number" class="form-control" name="quantity" id="quantity" readonly>
                                            <span class="text-danger" id="quantityErrorMsg"></span>
                                        </div>
                                        <div class="col-sm-3 col-12">
                                            <label for="purchase_rate">Purchase Rate</label>
                                            <input type="text" class="form-control" name="purchase_rate" id="purchase_rate" readonly  step=".01" oninput="validate(this)">
                                            <span class="text-danger" id="purchaseRateErrorMsg"></span>
                                        </div>
                                        <div class="col-sm-3 col-12">
                                            <label for="mode">Vat Rate</label>
                                            <select name="vat_rate" id="vat_rate" class="form-control" disabled>
                                                <option value=""></option>
                                                @foreach ($vatRates as $vatRate)
                                                    <option value="{{$vatRate->id}}">{{$vatRate->name}}</option>                             
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="vatRateErrorMsg"></span>
                                        </div>
                                        <div class="col-sm-3 col-12">
                                            <label for="vat_amount">Vat Amount</label>
                                            <input type="number" class="form-control" name="vat_amount" id="vat_amount" value="{{old('vat_amount')}}" readonly step=".01">
                                            <span class="text-danger" id="vatAmountRateErrorMsg"></span>
                                        </div>
                                        <div class="col-sm-3 col-12">
                                            <label for="total_amount">Total Amount</label>
                                            <input type="number" class="form-control" name="total_amount" id="total_amount" value="{{old('total_amount')}}" readonly  step=".01">
                                        </div>
                                        <div class="col-sm-3 d-flex pt-1">
                                            <button class="btn btn-success btn-sm p-1 m-0" id="add_product"><i class="bx bx-plus"></i></button>
                                            <button class="btn btn-warning btn-sm ml-1 p-1 m-0" id="refresh"><i class="bx bx-refresh"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered" id="itemListRemove">
                                <thead class="user-table-body">
                                    <tr>
                                        <th>Barcode</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Vat</th>
                                        <th scope="col">Pur. Rate</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tempLists">
                                    
                                    <tr class="border-top">
                                        <td colspan="6"  class="text-right">Amount (AED): </td>
                                        <td colspan="2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right">VAT:</td>
                                        <td colspan="2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right">Net Amount (AED):</td>
                                        <td colspan="2">
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-primary mr-1" id="new_product">New</button>
                                <button type="submit" class="btn btn-primary" id="form_submmit" disabled>Save</button>
                            </div>                  
                        </form>
                    </div>
                    <div class="table-responsive col-md-2 col-sm-2 col-12 col-lg-2">
                        <div class="d-flex">
                            <div class="mr-auto">
                                <h5>PO No</h5>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                                    <i class='bx bx-filter'></i>
                                  </button>
                            </div>
                         </div>
                        <div class="purchase-items ">
                            <ul>
                                @foreach ($pr_temps as $pr_temp)
                                <li>
                                    <a href="{{route('item-purchase.show', $pr_temp->id)}}">{{$pr_temp->purchase_no}}</a>
                                    <small>
                                        {{$pr_temp->gr_details_check($pr_temp->purchase_no)}}
                                        /{{$pr_temp->purchase_details->sum("quantity")}}
                                    </small>
                                </li>
                                @endforeach
                            </ul>
                            <ul id="po_list_show">
                                @foreach ($product_purchases as $product_purchase)                                    
                                    <li>
                                        <a href="{{route('item-purchase.show', $product_purchase->id)}}">{{$product_purchase->purchase_no}}</a>
                                        <small>
                                            {{$product_purchase->gr_details_check($product_purchase->purchase_no)}}
                                            /{{$product_purchase->purchase_details->sum("quantity")}}
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            {{$product_purchases->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
<!-- PO filter Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">PO Filter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="approval" name="approval" onclick="po_filter()">
                            <label class="form-check-label" for="approval">
                            PO Approval
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="101" id="completed" name="completed" onclick="po_filter()">
                            <label class="form-check-label" for="completed">
                            PO Completed
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="0" id="process" name="process" onclick="po_filter()">
                            <label class="form-check-label" for="process">
                            PO Process
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="99" id="revise" name="revise" onclick="po_filter()">
                            <label class="form-check-label" for="revise">
                                PO Revise
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="select-all">
                            <label class="form-check-label" for="select-all">All Select</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary btn-sm float-right mt-1"  data-dismiss="modal" id="filter_check">Check</button>
            </div>
        </div>
    </div>
</div>
  <!-- new customer add Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Customer Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('new-supplier') }}" method="POST" id="customerAddNew">
                    @csrf
                    <div class="row">
                        <div class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Party Code</label>
                                    <input type="text" id="" class="form-control" name="" value="{{ $cc }}" placeholder="Party Code" disabled readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Party Name</label>
                                    <input type="text" id="pi_name" class="form-control" name="pi_name" value="{{ isset($costCenter) ? $costCenter->pi_name : '' }}" placeholder="Party Name" required>
                                    @error('pi_name')
                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Party Type</label>
                                    <select name="pi_type" class="common-select2" style="width: 100% !important"
                                        id="pi_type" required>
                                        <option value="">Select...</option>
                                        @foreach ($costTypes as $item)
                                        <option value="{{ $item->title }}" {{ isset($costCenter) ? ($costCenter->pi_type == $item->title ? 'selected' : '') : '' }}> {{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('pi_type')
                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>TRN No</label>
                                    <input type="text" id="trn_no2" class="form-control" name="trn_no" value="{{ isset($costCenter) ? $costCenter->trn_no : '' }}" placeholder="TRN Number">
                                    @error('trn_no')
                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Address</label>
                                    <input type="text" id="address2" class="form-control" name="address" value="{{ isset($costCenter) ? $costCenter->address : '' }}" placeholder="Address">
                                    @error('address')
                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Contact Person</label>
                                    <input type="text" id="con_person" class="form-control" name="con_person"
                                        value="{{ isset($costCenter) ? $costCenter->con_person : '' }}"
                                        placeholder="Contact Person">
                                    @error('con_person')
                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Mobile Phone No</label>
                                    <input type="number" id="con_no" class="form-control" name="con_no" value="{{ isset($costCenter) ? $costCenter->con_no : '' }}" placeholder="Mobile No">
                                    @error('con_no')
                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Phone No</label>
                                    <input type="number" id="phone_no" class="form-control" name="phone_no" value="{{ isset($costCenter) ? $costCenter->phone_no : '' }}" placeholder="Phone No">
                                    @error('phone_no')
                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>Email</label>
                                    <input type="text" id="email" class="form-control" name="email" value="{{ isset($costCenter) ?$costCenter->email : '' }}" placeholder="Email">
                                    @error('email')
                                    <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 d-flex justify-content-end ">
                                    <button type="submit" class="btn btn-primary mr-1">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
   <!-- End Modal -->

@endsection

@push('js')
<script>
    document.getElementById("date").valueAsDate = new Date();
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN':'{{ csrf_token() }}'
        }
    });
    let item_price = 0;
    var validate = function(e) {
        var t = e.value;
        e.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 4)) : t;
        var max = Number(item_price);                                     
        var min = Number(t);
        if (min > max) {
            $('#purchase_rate').val(max);
            document.getElementById('add_product').setAttribute("disabled", "");
            // document.getElementById('add_product').removeAttribute('disabled');
        };
        if(min < max){
            document.getElementById('add_product').removeAttribute('disabled');
        }
    }

    // pay days count
    let selectID = document.getElementById('pay_term');
    selectID.addEventListener('change', function(){
        let currentDate = new Date();
        let addNumberOfDays = this.value;
        var myDate = new Date(new Date().getTime()+(Number(addNumberOfDays)*24*60*60*1000));
        document.getElementById('pay_date').valueAsDate = new Date(myDate);
    });
    function removeTempItem() {
        let purchase_no = document.getElementById("purchase_no").value;
        if(purchase_no){
            $.ajax({
                url:"{{URL::to('all-po-item-delete')}}",
                data:{
                    "purchase_no":purchase_no
                },
                type:"POST",
                success:function(response){
                }
            });
        }
    };
    window.onload = removeTempItem();
     // supplier information
    $("#supplier_id").change(function (e) {
        e.preventDefault();
        var supplier_id = $('#supplier_id option:selected').val();
        $.ajax({
            type:"post",
            url: "{{URL::to('supplier-information')}}",
            data:{
                "supplier_id":supplier_id
            },
            success:function(data){
                document.getElementById('trn').value = data.trn_no;
                document.getElementById('address').value = data.address;
                document.getElementById('contact_no').value = data.con_no;
            }
        });
    });
    // input information get    
    let serial_info = document.getElementById("purchase_no");
    let temp_purchase_no_info = document.getElementById("temp_purchase_no");
    let brand_info = document.getElementById("brand_id");
    let group_info = document.getElementById("group_id");
    let item_list_info = document.getElementById("item_list_id");
    let shipping_id_val = document.getElementById("shipping_id");
    let purchase_rate_val = document.getElementById("purchase_rate");
    let quantity_val = document.getElementById("quantity");
    let vat_rate_info = document.getElementById("vat_rate");
    let taxable_supplies_info = document.getElementById("taxable_supplies");
    let vat_amount_val = document.getElementById("vat_amount");
    let total_amount_val = document.getElementById("total_amount");
    let edit_purchase = document.getElementById("edit_purchase");
    let purchase_no = document.getElementById("purchase_no");
    let project_id = document.getElementById("project_id");
    let tax_invoice_no = document.getElementById("tax_invoice_no");
    let supplier_id = document.getElementById("supplier_id");
    let pay_mode = document.getElementById("pay_mode");
    let pay_term = document.getElementById("pay_term");
    let pay_date = document.getElementById("pay_date");
    let date = document.getElementById("date");
    let shipping_id = document.getElementById("shipping_id");
    let add_product = document.getElementById("add_product");
    let new_product = document.getElementById("new_product");
    let form_submmit = document.getElementById("form_submmit");
    let pr_id = document.getElementById("pr_id");
    document.getElementById('pay_date').valueAsDate = new Date();
    new_product.addEventListener("click", function(e){
        e.preventDefault();
        pr_id.removeAttribute('disabled');
        tax_invoice_no.removeAttribute("readonly");
        date.removeAttribute("readonly");
        supplier_id.removeAttribute("disabled");
        pay_mode.removeAttribute("disabled");
        pay_term.removeAttribute("disabled");
        shipping_id_val.removeAttribute("readonly");
        item_list_info.removeAttribute("disabled");
        vat_rate_info.removeAttribute("disabled");
        new_product.setAttribute("disabled", "");
    });
    item_list_info.addEventListener("change", function(e){
        var id_2 = $(this).children(":selected").attr("id");
        e.preventDefault();
        $.ajax({
            url:"{{URL::to('item-qty-get')}}",
            type:"POST",
            data:{
                "item_id":this.value,
                "purchase_no":pr_id.value
            },
            success:function(response){
                quantity_val.value = response[0];
                document.getElementById("purchaseRateErrorMsg").innerHTML = "Sell Price is "+response[1];
                purchase_rate_val.removeAttribute("readonly");
                purchase_rate_val.value = "";
                item_price = response[1];
            }
        });
    });
    pr_id.addEventListener("change", function(e){
        removeTempItem();
        e.preventDefault();
        $.ajax({
            url:"{{URL::to('item-list-get')}}",
            type:"POST",
            datatype:"json",
            data:{
                "id":this.value
            },
            success:function(response){
                var element_1 = response[0];
                var element_2 = response[1];
                var optionHtml = '<option value=""> Select Name </option>';
                element_1.forEach(function(element, index){
                    optionHtml += "<option id='"+element_2[index].style_code+"' value='"+element.id+"'>"+element.item_name+"</option>";
                });
                $("#item_list_id").html(optionHtml);
            }
        });
        $.ajax({
            url:"{{URL::to('project-name-get')}}",
            type:"POST",
            data:{
                "purchase_no":this.value
            },
            success:function(response){
                document.getElementById("project_id").value = response['id'];
                document.getElementById("project_name").value = response['proj_name'];
            }
        });
    });
    function optionCount(){
        let optionNumber = $('#item_list_id option').length;
        if(Number(optionNumber) == 1){
            form_submmit.removeAttribute('disabled');
        }
    }
    let vat_rate = 0;
    vat_rate_info.addEventListener("change", function(e){
        if(vat_rate_info.value){
            $.ajax({
                type:"post",
                url: "{{URL::to('vat-type-value')}}",
                data:{
                    "vat_type_id":vat_rate_info.value
                },
                success:function(data){
                    vat_rate = data;
                    total_amount_count()
                }
            });
        }
    });
    // vat amount coute
    vat_rate_info.addEventListener("change", function(){
        total_amount_count();
    });
    purchase_rate_val.addEventListener('change', function(){
        total_amount_count();
    });
    quantity_val.addEventListener('change', function(){
        total_amount_count();
    });
    // total amount count
    function total_amount_count(){
        let total = Number(purchase_rate_val.value)*quantity_val.value;
        let vat_amount = (total * vat_rate) / 100;
        let total_amount_with_amount = total + vat_amount;
        vat_amount_val.value = vat_amount.toFixed(2);
        total_amount_val.value = total_amount_with_amount.toFixed(2);
    }
    // temporary item store in table
    $('#add_product').on('click',function(e){
        let style_id = $("#item_list_id").children(":selected").attr("id");
        e.preventDefault();
        $.ajax({
          url: "{{URL::to('temp-po-item-store')}}",
          type:"post",
          data:{
            purchase_no:serial_info.value,
            item_list_id:item_list_info.value,
            purchase_rate:purchase_rate_val.value,
            quantity:quantity_val.value,
            vat_rate:vat_rate_info.value,
            vat_amount:vat_amount_val.value,
            total_amount:total_amount_val.value,
            style_id:style_id,
            pr_no:pr_id.options[pr_id.selectedIndex].text,
          },
          success:function(response){
            document.getElementById("tempLists").innerHTML = response;
            $(`#item_list_id #${style_id}`).remove();
            item_list_info.selectedIndex = 0;
            vat_rate_info.selectedIndex = 0;
            vat_rate = 0;
            quantity_val.value = '';
            purchase_rate_val.value = '';
            vat_amount.value = '';
            total_amount.value = '';
            document.getElementById('itemListErrorMsg').innerHTML = '';
            document.getElementById('purchaseRateErrorMsg').innerHTML = '';
            document.getElementById('quantityErrorMsg').innerHTML = '';
            document.getElementById('vatRateErrorMsg').innerHTML = '';
            document.getElementById('vatAmountRateErrorMsg').innerHTML = '';
            optionCount();
            purchase_rate_val.setAttribute("readonly", "");
            item_price = 0;
          },
            error:function(response) {
                if(response.responseJSON.errors.item_list_id){
                    document.getElementById('itemListErrorMsg').innerHTML = response.responseJSON.errors.item_list_id;
                }else{
                    document.getElementById('itemListErrorMsg').innerHTML = '';
                }
                if(response.responseJSON.errors.purchase_rate){
                    document.getElementById('purchaseRateErrorMsg').innerHTML = response.responseJSON.errors.purchase_rate;
                }else{
                    document.getElementById('purchaseRateErrorMsg').innerHTML = '';
                }
                if(response.responseJSON.errors.quantity){
                    document.getElementById('quantityErrorMsg').innerHTML = response.responseJSON.errors.quantity;
                }else{
                    document.getElementById('quantityErrorMsg').innerHTML = '';
                }
            },
        });
    });
    // product form reset 
    let productReset = document.getElementById('refresh');
    productReset.addEventListener("click", function(e){
        e.preventDefault();
        item_list_info.selectedIndex = 0;
        vat_rate_info.selectedIndex = 0;
        purchase_rate_val.value = "";
        quantity_val.value = "";
        vat_amount_val.value = "";
        total_amount_val.value = "";
    });
    
    $(document).on("click", ".row-delete", function(e) {
        e.preventDefault();
        var $ele = $(this).parent().parent();
        var id= $(this).val();
		$.ajax({
			url: "{{URL('one-po-item-delete')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
                purchase_no:serial_info.value,
			},
			success: function(response){				
                document.getElementById("tempLists").innerHTML = response;
			}
		});
	});
    $(document).on("click", ".row-edit", function(e) {
        e.preventDefault();
        var $ele = $(this).parent().parent();
        var id= $(this).val();
        console.log(id);
		$.ajax({
			url: "{{URL('one-po-item-edit')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
                purchase_no:serial_info.value,
			},
			success: function(response){
                console.log(response);
                let element_1 = response[0];
                let element_2 = response[1];
                let element_3 = response[2];
                let optionHtml = "<option selected id='"+element_2.style_no+"' value='"+element_1.id+"'>"+element_1.item_name+"</option>";
                $("#item_list_id").append(optionHtml);
                document.getElementById("purchaseRateErrorMsg").innerHTML = "Sell Price is "+element_1.total_amount;
                purchase_rate_val.value = element_3.purchase_rate;
                quantity_val.value = element_3.quantity;
                vat_amount_val.value = element_3.vat_amount;
                total_amount_val.value = element_3.total_amount;
                purchase_rate_val.removeAttribute("readonly");
                item_price = element_1.total_amount;
                // $("#itemList").on('click', '.row-edit', function () {
                //     $(this).closest('tr').remove();
                // });
			}
		});
	});
    $(document).ready(function() {
        // Page Script
        $('#select-all').click(function (event) {
            if (this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function () {
                    this.checked = false;
                });
            }
            po_filter();
        });        
    });
    function po_filter(){
        var filter_value = [];
        $.each($("input:checkbox[type='checkbox']:checked"), function () {
            filter_value.push($(this).val());
        });
        console.log(filter_value);
        $.ajax({
            url: "{{URL::to('po-filter')}}",
            type:"post",
            data:{
                filter_value:filter_value,
            },
            success:function(response){
                document.getElementById("po_list_show").innerHTML = response;
            }
        });
    }
    $("#customerAddNew").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var pi_name = $("#pi_name").val();
        var pi_type = $("#pi_type").val();
        var trn_no = $("#trn_no2").val();
        var address = $("#address2").val();
        var con_person = $("#con_person").val();
        var con_no = $("#con_no").val();
        var phone_no = $("#phone_no").val();
        var email = $("#email").val();
        // alert(mobile);
        $.ajax({
            url: url,
            method: "POST",
            data: {
                pi_name: pi_name,
                pi_type: pi_type,
                trn_no: trn_no,
                address: address,
                con_person: con_person,
                con_no: con_no,
                phone_no: phone_no,
                phone_no: phone_no,
                email: email,
                '_token': '{{ csrf_token() }}'
            },
            success: function(response) {
                $(".customer").empty().append(response.page);
                $("div.customer-select select").val(response.newCustomer.id);
                $("#trn").val(response.newCustomer.trn_no);
                $("#contact_no").val(response.newCustomer.con_no);
                $("#address").val(response.newCustomer.address);
                $("#customerModal").modal('hide');
            }
        })
    });
</script>
@endpush