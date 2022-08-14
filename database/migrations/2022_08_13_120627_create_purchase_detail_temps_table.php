<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_detail_temps', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_no');
            $table->string('pr_no');
            $table->integer('brand_id');
            $table->integer('group_id')->nullable();
            $table->integer('item_id');
            $table->decimal('purchase_rate', 10, 3);
            $table->integer('quantity');
            $table->string('unit')->nullable();
            $table->string('vat_rate');
            $table->decimal('vat_amount', 10, 3);
            $table->decimal('total_amount', 10, 2);
            $table->string('taxable_supplies')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_detail_temps');
    }
}
