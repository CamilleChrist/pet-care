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
        Schema::table('vaccination_records', function (Blueprint $table) {
            $table->dropForeign(['vaccine_id']);
            $table->foreign('vaccine_id')
                ->references('id')
                ->on('vaccines')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccination_records', function (Blueprint $table) {
            $table->dropForeign(['vaccine_id']);
            $table->foreign('vaccine_id')->references('id')->on('vaccines');
        });
    }
};
