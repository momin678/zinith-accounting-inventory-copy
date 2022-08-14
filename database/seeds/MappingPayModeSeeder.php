<?php

use App\MappingPayMode;
use Illuminate\Database\Seeder;

class MappingPayModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MappingPayMode::create([
            'title'=>'Cash',
        ]);
        MappingPayMode::create([
            'title'=>'Credit',
        ]);
        MappingPayMode::create([
            'title'=>'Bank Cheque',
        ]);
        MappingPayMode::create([
            'title'=>'On Line Bank Transfer',
        ]);
        MappingPayMode::create([
            'title'=>'VAT',
        ]);
    }
}
