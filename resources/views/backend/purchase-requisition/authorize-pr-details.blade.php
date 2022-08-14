@extends('layouts.backend.app')
@push('css')
@endpush
@section('title', 'authorize pr details')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="row" id="table-bordered">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <h5>Purchase Requisition Authorize</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-12">
                                                <label for="mode">PR No</label>
                                                <input type="text" required value="{{$authorize_requisition_info->purchase_no}}" readonly class="form-control" name="purchase_no" id="purchase_no">
                                            </div>
                                            <div class="col-sm-4 col-12">
                                                <label for="">PR Date:</label>
                                                <input type="text" readonly value="{{date('d-m-Y', strtotime($authorize_requisition_info->date))}}" class="form-control">
                                            </div>
                                            <div class="col-sm-4 col-12">
                                                <label for="project_id">Branch Name</label>
                                                <input type="text" readonly value="{{$authorize_requisition_info->projectInfo->proj_name}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered">
                                <thead class="user-table-body">
                                    <tr>
                                        <th scope="col">Barcode</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Qty</th>
                                    </tr>
                                </thead>
                                <tbody id="tempLists">
                                    @foreach ($purchase_items as $item)
                                    <tr class="data-row">
                                        <td>{{$item->itemName->barcode}}</td>
                                        <td>{{$item->itemName->item_name}}</td>
                                        <td>{{$item->unit}}</td>
                                        <td>{{$item->quantity}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                        <div class="mb-3">
                            <a class="btn btn-info" href="#" onclick="apr_reviece()">Revise</a>
                            <a class="btn btn-warning" href="{{route('authorize-pr-rejected', $authorize_requisition_info->id)}}">Rejected</a>
                            <a class="btn btn-success" href="{{route('authorize-pr-submit', $authorize_requisition_info->id)}}">Authorize</a>
                        </div>
                        <div id="authorizeRejectedForm" style="display: none;">
                            <form action="{{route("authorize-pr-reviece")}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-10 col-12">
                                        <label for="mode">Comment</label>
                                        <input type="text" required placeholder="Comment" class="form-control" name="comment">
                                        <input type="hidden" required value="{{$authorize_requisition_info->purchase_no}}" class="form-control" name="purchase_no">
                                    </div>
                                    <div class="col-sm-2 col-12 mt-2">
                                        <button class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@push('js')
    <script>
        function apr_reviece() {
            var x = document.getElementById("authorizeRejectedForm");
            if (x.style.display === "block") {
                x.style.display = "none";
            } else {
                x.style.display = "block";
            }
        }
    </script>
@endpush