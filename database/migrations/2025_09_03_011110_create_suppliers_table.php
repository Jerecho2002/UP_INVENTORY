<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('supplier_name', 180)->unique();
            $table->string('contact_no', 60)->nullable();
            $table->string('email', 60)->nullable();
            $table->string('address', 255)->nullable();
            $table->tinyInteger('status')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
