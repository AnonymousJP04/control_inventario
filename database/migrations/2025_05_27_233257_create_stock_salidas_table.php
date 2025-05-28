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
        Schema::create('stock_salidas', function (Blueprint $table) {

            $table->id();
            $table->foreignId('producto_id')-> constrained('productos');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('cantidad');
            $table->string('motivo')->nullable();
            $table->date('fecha')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_salidas');
    }
};
