<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acknowledgement_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('par_date');
            $table->unsignedInteger('accountable_persons_id');
            $table->unsignedInteger('issued_by_id');
            $table->unsignedInteger('fund_source_id');
            $table->unsignedInteger('property_id')->nullable();
            $table->string('remarks', 255)->nullable();
            $table->unsignedInteger('created_by');
            $table->timestamps();

            $table->foreign('accountable_persons_id')->references('id')->on('accountable_persons')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('issued_by_id')->references('id')->on('accountable_persons')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('fund_source_id')->references('id')->on('fund_sources')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('property_id')->references('id')->on('properties')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acknowledgement_receipts');
    }
};
