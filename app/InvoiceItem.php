<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use  SoftDeletes;

    public function item()
    {
        // return 1;
        return $this->belongsTo(ItemList::class);
    }

}
