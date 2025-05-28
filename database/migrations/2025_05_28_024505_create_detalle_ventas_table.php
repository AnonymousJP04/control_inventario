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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade'); // Clave foránea a la tabla ventas
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad')->default(0); // Cantidad del producto vendido, por defecto 0
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0); // Descuento por item
            $table->decimal('total', 10, 2); // total del item (subtotal - descuento)
            $table->foreignId('user_id')->constrained('users'); // Usuario que registró este detalle (opcional, podría ser el mismo de la venta)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

     //Relaciones con ventas y productos
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};