<?php

namespace App;

use App\Models\AccountHead;
use App\Models\CostCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use  SoftDeletes;

    public function project()
    {
        return $this->belongsTo(ProjectDetail::class, 'project_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function PartyInfo()
    {
        return $this->belongsTo(PartyInfo::class, 'party_info_id');
    }

    public function accHead()
    {
        return $this->belongsTo(AccountHead::class, 'ac_head_id');
    }

    public function taxRate()
    {
        return $this->belongsTo(VatRate::class, 'tax_rate');
    }

    public function creditPartyInfo()
    {
        return $this->belongsTo(PartyInfo::class, 'credit_party_info');
    }
}
