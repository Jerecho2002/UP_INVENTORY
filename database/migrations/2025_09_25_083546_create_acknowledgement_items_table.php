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
            $table->integer('quantity')->nullable();
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->tinyInteger('status')->nullable();

            $table->foreign('acknowledgement_id')->references('id')->on('acknowledgement_receipts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acknowledgement_items');
    }
};
