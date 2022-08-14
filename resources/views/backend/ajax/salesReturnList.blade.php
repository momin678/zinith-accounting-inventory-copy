@foreach (App\SaleReturnTemp::where('invoice_no',$itemReturn->invoice_no)->get() as $item)
<tr class="data-row">
	<td>{{ $i }}</td>
	<td>{{ $item->barcode }}</td>
	<td>{{ $item->item->item_name }}</td>
	<td>{{ $item->quantity }}</td>
    <td>{{ $item->unit }}</td>
	<td>{{ $item->unit_price }}</td>
	<td>{{ $item->vat_rate }}</td>
	<td></td>
	<td>{{ $item->unit_price*$item->quantity }}</td>


	<td class="text-right">

        {{-- <button class="btn btn-danger invoice-item-delete" >Delete</button> --}}
        <span class="btn btn-warning invoice-item-delete" id="" data_target="{{ route('itemDelete',$item) }}"><i class="bx bx-trash"></i></span>


	</td>
</tr>
<?php $i++; ?>
@endforeach


