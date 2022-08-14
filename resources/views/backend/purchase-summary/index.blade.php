@extends('layouts.backend.app')
@section('title', 'Purchase Summary')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        td{
            text-align: right !important;
        }
        th{
            text-transform: uppercase;
        }
    </style>
@endpush

@section('content')
@php

@endphp
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-md-4">
                            <h4>Purchase Summary</h4>
                        </div>
                        <div class="col-md-2 text-right  col-left-padding">
                            <form action="#" method="GET">
                                {{-- @csrf --}}
                                <div class="row form-group  col-left-padding">
                                    <input type="text" class="form-control col-9 " name="date"
                                        placeholder="Select Date" onfocus="(this.type='date')" id="date" required>
                                    <button class="bx bx-search col-3 btn-warning btn-block" type="submit"></button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4  col-left-padding">
                            <form action="#" method="GET">
                                {{-- @csrf --}}
                                <div class="row form-group">
                                    <div class="col-5 col-right-padding">
                                        <input type="text" class="form-control" name="from"
                                        placeholder="From" onfocus="(this.type='date')" id="from" required>
                                    </div>
                                    <div class="col-5  col-left-padding col-right-padding">
                                        <input type="text" class="form-control" name="to"
                                        placeholder="To" onfocus="(this.type='date')" id="to" required>
                                    </div>
                                    <button class="bx bx-search col-2 btn-warning btn-block" type="submit"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                @foreach ($styles as $style)
                                    <tr>
                                        <td class="text-center" colspan="15"> {{ $style->style_name }} </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="width: 130px">COLOR</td>
                                        @foreach ($sizes as $size)
                                            @if ($size->use_size($style->id))
                                                <td>{{$size->group_name}}</td>
                                            @endif
                                        @endforeach
                                        <td class="text-center">Total</td>
                                    </tr>
                                    @foreach ($colors as $color)
                                        @if ($color->use_color($style->id))
                                        <tr>
                                            @php $total = 0; @endphp
                                            <td>{{$color->name}}</td>
                                            @foreach ($sizes as $size)
                                                @if ($size->use_size($style->id))
                                                    @if ($size->item_qty($style->id, $color->id))
                                                        @php $total += $size->item_qty($style->id, $color->id); @endphp
                                                        <td>{{$size->item_qty($style->id, $color->id)}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @endif
                                            @endforeach
                                            <td>{{$total}}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@push('js')

@endpush
