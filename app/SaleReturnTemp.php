<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleReturnTemp extends Model
{
    public function item()
    {
        return $this->belongsTo(ItemList::class);
    }
}
