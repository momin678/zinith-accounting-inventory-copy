<?php

namespace App;

use App\Models\AccountHead;
use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
    protected $fillable = ['fld_txn_type', 'fld_txn_mode', 'fld_ac_code', 'fld_ac_name'];
    public function accountHead(){
        return $this->belongsTo(AccountHead::class, 'fld_ac_name', 'id');
    }
    public function mapping_txn_type(){
        return $this->belongsTo(MappingTxnType::class, 'fld_txn_type');
    }
    public function mapping_pay_mode(){
        return $this->belongsTo(MappingPayMode::class, 'fld_txn_mode');
    }
}
