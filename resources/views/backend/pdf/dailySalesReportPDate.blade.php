@extends('layouts.pdf.app')
@push('css')
<style>
    th td{
        color: black !important;
        text-align: center !important;
    }

    @media print {
@page { margin: 0; }
.page-break { page-break-after: always; }
}

</style>
@endpush
  @section('content')


  <div class="container py-4  page-break">
    <!-- BEGIN: Content-->
    <div class="content-overlay"></div>
    <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $date }} Sales Report</h4>
                           </div>
                           <div class="col-md-4 text-right">

                           </div>
                    </div>

                    <div class="row pt-2">

                        <table   class="table table-sm table-bordered">
                            <tr>
                                <th class="text-center" colspan="14">Sales of {{ $date }}</th>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">Item</td>
                                <td class="text-center">COLOR</td>
                            <td class="text-center">Xs</td>
                            <td class="text-center">S</td>
                            <td class="text-center">M</td>
                            <td class="text-center">L</td>
                            <td class="text-center">XL</td>
                            <td class="text-center">Xxl</td>
                            <td class="text-center">Xxxl</td>
                            <td class="text-center">Total Pcs</td>
                            <td class="text-center">Rate per Pcs</td>
                            <td class="text-center">Total Value</td>
                            </tr>
                            @foreach (App\ItemList::select('style_id')->distinct()->get() as $it)
                            @php
                                $style=App\Style::where('id', $it->style_id)->first();
                                $row=0;
                                $styleQty=0;
                                $styleAmount=0;
                                $colorCount=App\ItemList::select('brand_id')->where('style_id',$it->style_id)->distinct()->get();

                            @endphp
                            <tr>
                                <td rowspan="{{ $colorCount->count() }}" colspan="2">{{ $style->style_name }}</td>
                                @foreach (App\ItemList::select('brand_id')->where('style_id',$it->style_id)->distinct()->get()
                                    as $color)
                                       @php
                                       $itmXs= App\ItemList::where('style_id', $it->style_id)->where('brand_id',$color->brand_id)->where('group_name','Xs')->first();
                                       $itmS= App\ItemList::where('style_id', $it->style_id)->where('brand_id',$color->brand_id)->where('group_name','S')->first();
                                       $itmM= App\ItemList::where('style_id', $it->style_id)->where('brand_id',$color->brand_id)->where('group_name','M')->first();
                                       $itmL= App\ItemList::where('style_id', $it->style_id)->where('brand_id',$color->brand_id)->where('group_name','L')->first();
                                       $itmXl= App\ItemList::where('style_id', $it->style_id)->where('brand_id',$color->brand_id)->where('group_name','Xl')->first();
                                       $itmXxl= App\ItemList::where('style_id', $it->style_id)->where('brand_id',$color->brand_id)->where('group_name','Xxl')->first();
                                       $itmXxxl= App\ItemList::where('style_id', $it->style_id)->where('brand_id',$color->brand_id)->where('group_name','Xxxl')->first();
                                       $brand=App\Brand::where('id',$color->brand_id)->first();
                                   @endphp
                                @if($row==0)

                                    <td>{{ $brand->name }}</td>
                                    <td>{{ $itmXsC= isset($itmXs)? $itmXs->dateSale($date):0 }}</td>
                                    <td>{{ $itmSC =isset($itmS)? $itmS->dateSale($date):0 }}</td>
                                    <td>{{ $itmMC=isset($itmM)? $itmM->dateSale($date):0 }}</td>
                                    <td>{{ $itmLC=isset($itmL)? $itmL->dateSale($date):0 }}</td>
                                    <td>{{ $itmXlC=isset($itmXl)? $itmXl->dateSale($date):0 }}</td>
                                    <td>{{$itmXxlC= isset($itmXxl)? $itmXxl->dateSale($date):0 }}</td>
                                    <td>{{ $itmXxxlC=isset($itmXxxl)? $itmXxxl->dateSale($date):0 }}</td>
                                    <td>{{ $colorQty= $itmXsC+$itmSC+$itmMC+$itmLC+$itmXlC+$itmXxlC+$itmXxxlC }}</td>
                                    <td>{{number_format((float)$brand->colorItemSaleRateDate($style,$brand,$date), 3,'.','') }}</td>
                                    <td>{{$colorAmount = $brand->colorItemSaleAmountDate($style,$brand,$date) }}</td>
                                    @php
                                        $row=1;
                                    @endphp

                                @else
                                <tr>
                                  <td>{{ $brand->name }}</td>
                                    <td>{{ $itmXsC= isset($itmXs)? $itmXs->dateSale($date):0 }}</td>
                                    <td>{{ $itmSC =isset($itmS)? $itmS->dateSale($date):0 }}</td>
                                    <td>{{ $itmMC=isset($itmM)? $itmM->dateSale($date):0 }}</td>
                                    <td>{{ $itmLC=isset($itmL)? $itmL->dateSale($date):0 }}</td>
                                    <td>{{ $itmXlC=isset($itmXl)? $itmXl->dateSale($date):0 }}</td>
                                    <td>{{$itmXxlC= isset($itmXxl)? $itmXxl->dateSale($date):0 }}</td>
                                    <td>{{ $itmXxxlC=isset($itmXxxl)? $itmXxxl->dateSale($date):0 }}</td>
                                    <td>{{$colorQty=  $itmXsC+$itmSC+$itmMC+$itmLC+$itmXlC+$itmXxlC+$itmXxxlC }}</td>
                                    <td>{{number_format((float)$brand->colorItemSaleRateDate($style,$brand,$date), 3,'.','') }}</td>
                                    <td>{{$colorAmount= $brand->colorItemSaleAmountDate($style,$brand,$date) }}</td>
                                </tr>
                                @endif

                                @php
                                    $styleQty= $styleQty+$colorQty;
                                    $styleAmount=$styleAmount+$colorAmount;
                                @endphp
                                @endforeach
                                <tr>
                                    <td colspan="9"></td>
                                    <td  style="font-weight: bold;">Total</td>
                                    <td style="font-weight: bold;">{{ $styleQty }}</td>
                                    <td  style="font-weight: bold;">Total</td>
                                    <td style="font-weight: bold;">{{ $styleAmount }}</td>
                                </tr>
                            </tr>
                            @endforeach

                        </table>

                    </div>

                </section>
                <!-- Widgets Statistics End -->



            </div>
        </div>
    <div class="row pt-3">
        <table class="table table-sm table-bordered" >
            <tr>
                <th>Prepared By</th>
                <th>Checked By</th>
                <th>Endorsed By</th>
                <th>Authorized By</th>
                <th>Authorized By</th>
                <th>Approved By</th>
            </tr>

            <tr>
                <td>Mahidul Islam Bappy</td>
                <td>Ridwanuzzaman</td>
                <td>Habibur Rahaman</td>
                <td>Md. Akhter Hosain</td>
                <td>S.M Arifen</td>
                <td>Salim Osman</td>


            </tr>

        </table>
 </div>
</div>


  @endsection
