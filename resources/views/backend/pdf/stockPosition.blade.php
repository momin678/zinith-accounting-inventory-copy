@extends('layouts.pdf.app')
@push('css')
<style>
    th td{
        color: black !important;
    }

    @media print {
@page { margin: 0; }
.page-break { page-break-after: always; }
}

</style>
@endpush
  @section('content')
  @foreach (App\ItemList::select('style_id')->distinct()->get() as $it)
  @php
      $style=App\Style::where('id', $it->style_id)->first();
  @endphp
  {{-- $style->styleSTockPositionCheck($style) --}}
                        @if(1==1)

  <div class="container py-4  page-break">
    <!-- BEGIN: Content-->
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <!-- Widgets Statistics start -->
                <div class="row">
                    <div class="col-md-12">
                        <h4>{{ date('d M Y') }} Stock Report</h4>
                    </div>
                </div>
                <div class="row pt-2">
                        {{-- $style->styleSTockPositionCheck($style --}}
                        <section class="container ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th class="text-center" colspan="12">
                                                    {{ $style->style_name }}  </th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" style="width: 130px">COLOR</th>
                                                <th class="text-center">Xs</th>
                                                <th class="text-center">S</th>
                                                <th class="text-center">M</th>
                                                <th class="text-center">L</th>
                                                <th class="text-center">Xl</th>
                                                <th class="text-center">Xxl</th>
                                                <th class="text-center">Xxxl</th>
                                                <th class="text-center">Total</th>
                                            </tr>
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
                                            {{-- $brand->stockPositionCheck($brand,$style) --}}
                                           @if( 1==1)
                                           <tr>
                                            <td><strong>{{ $brand->name }}</strong></td>
                                            <td>{{ $itmXsC= isset($itmXs)? $itmXs->todayOpeningStock():0 }}</td>
                                            <td>{{ $itmSC =isset($itmS)? $itmS->todayOpeningStock():0 }}</td>
                                            <td>{{ $itmMC=isset($itmM)? $itmM->todayOpeningStock():0 }}</td>
                                            <td>{{ $itmLC=isset($itmL)? $itmL->todayOpeningStock():0 }}</td>
                                            <td>{{ $itmXlC=isset($itmXl)? $itmXl->todayOpeningStock():0 }}</td>
                                            <td>{{ $itmXxlC=isset($itmXxl)? $itmXxl->todayOpeningStock():0 }}</td>
                                            <td>{{ $itmXxxlC=isset($itmXxxl)? $itmXxxl->todayOpeningStock():0 }}</td>
                                            <td>{{  $itmXsC+$itmSC+$itmMC+$itmLC+$itmXlC+$itmXxlC+$itmXxxlC }}</td>
                                        </tr>

                                        <tr>
                                            <td><small>Sale</small></td>
                                            <td>{{ $itmXsC= isset($itmXs)? $itmXs->saleToday():0 }}</td>
                                            <td>{{ $itmSC =isset($itmS)? $itmS->saleToday():0 }}</td>
                                            <td>{{ $itmMC=isset($itmM)? $itmM->saleToday():0 }}</td>
                                            <td>{{ $itmLC=isset($itmL)? $itmL->saleToday():0 }}</td>
                                            <td>{{ $itmXlC=isset($itmXl)? $itmXl->saleToday():0 }}</td>
                                            <td>{{$itmXxlC= isset($itmXxl)? $itmXxl->saleToday():0 }}</td>
                                            <td>{{ $itmXxxlC=isset($itmXxxl)? $itmXxxl->saleToday():0 }}</td>
                                            <td>{{  $itmXsC+$itmSC+$itmMC+$itmLC+$itmXlC+$itmXxlC+$itmXxxlC }}</td>
                                        </tr>


                                        <tr>
                                            <td><small>Current Stock</small></td>
                                            <td>{{$itmXsC= isset($itmXs)? $itmXs->itemStock():0 }}</td>
                                            <td>{{ $itmSC =isset($itmS)? $itmS->itemStock():0 }}</td>
                                            <td>{{$itmMC= isset($itmM)? $itmM->itemStock():0 }}</td>
                                            <td>{{ $itmLC=isset($itmL)? $itmL->itemStock():0 }}</td>
                                            <td>{{$itmXlC= isset($itmXl)? $itmXl->itemStock():0 }}</td>
                                            <td>{{$itmXxlC= isset($itmXxl)? $itmXxl->itemStock():0 }}</td>
                                            <td>{{$itmXxxlC= isset($itmXxxl)? $itmXxxl->itemStock():0 }}</td>
                                            <td>{{  $itmXsC+$itmSC+$itmMC+$itmLC+$itmXlC+$itmXxlC+$itmXxxlC }}</td>
                                        </tr>
                                           @endif
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>



                </div>
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
@endif
  @endforeach

  @endsection
