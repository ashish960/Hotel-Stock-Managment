<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stockreports', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('vendor_name')->nullable();
            $table->float('item_quantity')->default('0')->comment("kg")->nullable();
            $table->float('inward_quantity')->default('0')->comment("kg")->nullble();
            $table->float('outward_quantity')->default('0')->comment("kg")->nullable();
            $table->float('item_price')->comment('/kg')->default('0')->nullable();
            $table->boolean('item_status')->comment('1:added,2:deducted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockreports');
    }
};
