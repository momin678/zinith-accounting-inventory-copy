<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_no');
            $table->integer('brand_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('item_id');
            $table->integer('item_barcode');
            $table->integer('style_code');
            $table->integer('purchase_rate')->nullable();
            $table->integer('quantity');
            $table->string('unit');
            $table->string('vat_rate')->nullable();
            $table->decimal('vat_amount',10,3)->nullable();
            $table->decimal('total_amount',10,3)->nullable();
            $table->string('taxable_supplies')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('purchase_requisition_details');
    }
}
