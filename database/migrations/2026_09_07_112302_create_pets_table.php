<?php

use App\Models\Breed;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->constrained();
            $table->foreignIdFor(Breed::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('photo_path')->nullable();
            $table->text('health_notes')->nullable();
            $table->date('last_vet_visit_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
