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
        Schema::create('game_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('seat_no')->nullable();
            $table->unique(['game_id', 'seat_no']);
            $table->integer('filled_seats')->default(0);
            $table->decimal('entry_fee', 10, 2);

            $table->enum('status', ['joined', 'lost', 'won'])
                ->default('joined');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_participants');
    }
};
