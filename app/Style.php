<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    protected $table = 'styles';
    protected $fillable = ['style_name', 'style_no'];
    public function items()
    {
        return $this->hasMany(ItemList::class,'style_id');
    }

    public function styleSTockPositionCheck($style)
    {
        $items= ItemList::where('style_id',$style->id)->get();
        foreach($items as $item)
        {
            if($item->todayOpeningStock() > 0)
            {
                return true;
            }
            if($item->itemStock() > 0)
            {
                return true;
            }
            if($item->saleToday() > 0)
            {
                return true;
            }
        }
        return false;
    }



    public function styleSTockPositionCheckDate($style,$date)
    {
        $items= ItemList::where('style_id',$style->id)->get();
        foreach($items as $item)
        {
            if($item->dateOpeningStock($date) > 0)
            {
                return true;
            }
            if($item->dateItemStock($date) > 0)
            {
                return true;
            }
            if($item->dateSale($date) > 0)
            {
                return true;
            }
        }
        return false;
    }


    public function styleStockPositionCheckRange($style,$from,$to)
    {
        $items= ItemList::where('style_id',$style->id)->get();
        foreach($items as $item)
        {
            if($item->dateOpeningStock($from) > 0)
            {
                return true;
            }
            if($item->saleRange($from,$to) > 0)
            {
                return true;
            }
            if($item->dateItemStock($to) > 0)
            {
                return true;
            }
        }
        return false;
    }


    public function styleItemSaleRate()
    {
        // dd($style, $brand);
        $totalQty=0;
        $totalAmount=0;
        $items= $this->hasMany(ItemList::class, 'style_id');
        foreach($items as $item)
        {
            $totalQty=$totalQty+$item->saleToday();
            $totalAmount=$totalAmount+$item->saleAmountToday();
        }

        if($totalQty==0)
        {
            return 0;
        }
        return $totalAmount/$totalQty;
    }

    public function colorSaleCount($date,$id)
    {
        $count=0;
        $items=ItemList::where('style_id',$id)->get();
        foreach($items as $item)
        {
            if($item->dateSale($date)>0)
            {
                $count=$count+1;
            }
        }
        return $count;
    }

    public function colorSaleCountToday($id)
    {
        $count=0;
        $items=ItemList::where('style_id',$id)->get();
        foreach($items as $item)
        {
            if($item->saleToday()>0)
            {
                $count=$count+1;
            }
        }
        return $count;
    }
}
