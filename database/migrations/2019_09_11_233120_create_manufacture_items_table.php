<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufactureItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacture_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('manufacture_id');
            $table->string('item_name');
            $table->decimal('quantity',50,2);
            $table->decimal('result',50,2)->nullable();
            $table->decimal('scrap',50,2)->nullable();
            $table->uuid('uom_id');
            $table->foreign('manufacture_id')->references('id')->on('manufactures')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('manufacture_items');
    }
}
