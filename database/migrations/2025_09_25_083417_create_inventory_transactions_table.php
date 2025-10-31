<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('inventory_item_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->date('date_released')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('inventory_item_id') ->references('id')->on('inventory_items') ->cascadeOnDelete() ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
