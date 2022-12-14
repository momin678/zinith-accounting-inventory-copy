<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('item_id');
            $table->string('style_id');
            $table->string('color_id');
            $table->string('size');
            $table->integer('invoice_id');
            $table->integer('barcode');
            $table->integer('quantity');
            $table->decimal('cost_price',12,3);
            $table->decimal('net_amount',12,3);
            $table->string('vat_rate');
            $table->decimal('vat_amount',12,3);
            $table->string('unit');
            $table->decimal('unit_price',12,3);
            $table->decimal('total_unit_price',12,3);
            $table->softDeletes();

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
        Schema::dropIfExists('invoice_items');
    }
}
