<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pedido;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Token público inadivinable para seguir el pedido sin exponer el id secuencial
            $table->string('public_token', 40)->nullable()->unique()->after('id');
        });

        // Backfill: token para los pedidos existentes
        Pedido::whereNull('public_token')->get()->each(function ($pedido) {
            $pedido->public_token = (string) Str::uuid();
            $pedido->save();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropUnique(['public_token']);
            $table->dropColumn('public_token');
        });
    }
};
