@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@endpush
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">

            <div class="content-body">
                <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row mr-1">
                        <div class="col-md-10 ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row pb-1 d-flex align-items-center">
                                        <div class="col-md-3">
                                            <h4>Delivery Note</h4>
                                        </div>
                                        <div class="col-md-9 text-right">
                                            <a href="{{ route('dnList') }}" class="btn btn-info btn-sm">Delivery Note List</a>
                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="row d-flex justify-content-end">
                            </div>

                            <div class="row details-view">


                            </div>
                        </div>
                        <div class="col-md-2 ">
                            <div class="row">
                                <h4>Delivery Notes <span></span></h4>
                                {{-- <div class="col-12 pb-1">
                                    <button class="btn btn-warning btn-sm btn-block" value="hide"
                                        id="searchSO">Search</button>
                                </div>
                                <div class="col-md-12">
                                    <div class="row search-class" style="display: none">
                                        <div class="col-md-12 d-flex justify-content-center ">
                                            <input type="text" class="form-control" id="dNo"
                                                placeholder="Sale Order No">
                                        </div>

                                        <div class="col-md-12 d-flex justify-content-center ">
                                            <input type="month" class="form-control" id="month"
                                                placeholder="Sale Order No">

                                        </div>
                                        <div class="col-md-12 d-flex justify-content-center pb-1">
                                            <input type="date" class="form-control" id="date"
                                                placeholder="Sale Order No">

                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row delivery-note">
                                    @foreach ($dNotes as $item)
                                        <div class="col-md-12 btn btn-light  mx-1 mb-1 text-center"
                                            id="sale-order-details"
                                            data_target="{{ route('deliveryNoteDetails', $item) }}">
                                            {{ $item->deliveryNote->delivery_note_no }}

                                        </div>
                                    @endforeach
                                </div>

                            </div>

                        </div>
                    </div>





                </section>
            </div>
        </div>
    </div>

    <!-- END: Content-->
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
    <script>
        // $(document).ready(function() {
        // Page Script
        // alert("Alhamdulillah");
        // });
    </script>

    <script>
        $(document).ready(function() {

            var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();

            $(document).on("keyup", "#dNo", function(e) {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('searchSODNo') }}",
                    method: "GET",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        // console.log(response);
                        $(".delivery-note").empty().append(response.page);
                    }
                })
            });
            $('#month').change(function() {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('searchSODNoMonth') }}",
                    method: "GET",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        // console.log(response);
                        $(".delivery-note").empty().append(response.page);
                    }
                })
            });

            $('#date').change(function() {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('searchSODNoDate') }}",
                    method: "GET",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        // console.log(response);
                        $(".delivery-note").empty().append(response.page);
                    }
                })
            });

            $(document).on("click", "#sale-order-details", function(e) {
                e.preventDefault();
                $(this).addClass('active-button-sale').siblings('div').removeClass('active-button-sale');

                var that = $(this);
                var urls = that.attr("data_target");
                // alert(urls);
                delay(function() {
                    $.ajax({
                        url: urls,
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        success: function(response) {
                            //   alert('ok');
                            console.log(response);
                            $(".details-view").empty().append(response.page);
                        },
                        error: function() {
                            // alert('Problem Found');
                        }
                    });
                }, 999);
            });

            $(document).on("click", "#searchSO", function(e) {
                var value = $(this).val();
                if (value == 'hide') {
                    $("#searchSO").val('show');
                    $(".search-class").show();

                } else {
                    $("#searchSO").val('hide');
                    $(".search-class").hide();
                }

            });
        });
    </script>
@endpush
