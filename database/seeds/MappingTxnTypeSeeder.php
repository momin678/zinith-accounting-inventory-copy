<?php

use App\MappingTxnType;
use Illuminate\Database\Seeder;

class MappingTxnTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MappingTxnType::create([
            'title'=>'Sale',
        ]);
        MappingTxnType::create([
            'title'=>'Purchase',
        ]);
        MappingTxnType::create([
            'title'=>'Income',
        ]);
        MappingTxnType::create([
            'title'=>'Expense',
        ]);
        MappingTxnType::create([
            'title'=>'VAT',
        ]);

    }
}
