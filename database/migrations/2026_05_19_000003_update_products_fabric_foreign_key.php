<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['fabric_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('fabric_id')->nullable()->change();
            $table->foreign('fabric_id')->references('id')->on('fabrics')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['fabric_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('fabric_id')->nullable(false)->change();
            $table->foreign('fabric_id')->references('id')->on('fabrics')->restrictOnDelete();
        });
    }
};
