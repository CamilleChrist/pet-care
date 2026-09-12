<?php

use App\Models\Pet;
use App\Models\Vaccine;
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
        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pet::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Vaccine::class)
                ->nullable()
                ->constrained();
            $table->string('custom_name')
                ->nullable();
            $table->date('administered_at')
                ->useCurrent();
            $table->date('next_due_at')
                ->nullable();
            $table->string('veterinarian_name')
                ->nullable();
            $table->string('clinic_name')
                ->nullable();
            $table->string('lot_number')
                ->nullable();
            $table->string('notes')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccination_records');
    }
};
