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
        Schema::create('detail_invoice', function (Blueprint $table) {
            $table->id();
            $table->string("coil_number");
            $table->double("width");
            $table->double("length");
            $table->double("thickness");
            $table->double("weight");
            $table->decimal('price', 20, 2);
            $table->unsignedBigInteger('id_invoice');
            $table->foreign('id_invoice')->references("id")->on('invoice')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_invoice');
    }
};
