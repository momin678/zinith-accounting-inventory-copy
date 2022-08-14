<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartyInfo extends Model
{
    use  SoftDeletes;
    protected $table = "party_infos";
    protected $fillable = ['pi_code', 'pi_name', 'pi_type', 'trn_no', 'address', 'con_person', 'con_no', 'phone_no', 'email'];
    

}
