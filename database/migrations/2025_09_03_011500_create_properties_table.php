<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('property_number', 80)->nullable()->unique();
            $table->date('date_acquired')->nullable();
            $table->unsignedInteger('fund_source_id');
            $table->unsignedInteger('pr_id');
            $table->unsignedInteger('po_id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('location_id');
            $table->string('remarks', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('fund_source_id')->references('id')->on('fund_sources')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('pr_id')->references('id')->on('purchase_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('po_id')->references('id')->on('purchase_orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('invoice_id')->references('id')->on('invoices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('location_id')->references('id')->on('locations')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
