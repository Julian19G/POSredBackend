<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('stock', 12, 2)->default(0)->change();
        });

        Schema::table('variantes', function (Blueprint $table) {
            $table->decimal('cantidad_por_variante', 10, 2)->change();
        });

        Schema::table('presentaciones', function (Blueprint $table) {
            $table->decimal('cantidad', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('stock')->default(0)->change();
        });
        Schema::table('variantes', function (Blueprint $table) {
            $table->integer('cantidad_por_variante')->change();
        });
        Schema::table('presentaciones', function (Blueprint $table) {
            $table->unsignedInteger('cantidad')->change();
        });
    }
};
