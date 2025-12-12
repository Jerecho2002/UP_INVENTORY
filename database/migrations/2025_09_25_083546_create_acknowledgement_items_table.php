<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acknowledgement_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('acknowledgement_id')->nullable();
            $table->unsignedBigInteger('inventory_item_id')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();

            $table->foreign('acknowledgement_id')->references('id')->on('acknowledgement_receipts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acknowledgement_items');
    }
};
