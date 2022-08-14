<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['brand_id', 'name', 'origin'];

    public function items()
    {
        return $this->hasMany(ItemList::class);
    }
    public function use_color($style_id){
        return $this->hasOne(ItemList::class, "brand_id", "id")->where("style_id", $style_id)->first();
    }
}
