<?php

namespace App\Models;

use App\ProjectDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostCenter extends Model
{
    use  SoftDeletes;

    public function project()
    {
        return $this->belongsTo(ProjectDetail::class, 'project_id');
    }

}
