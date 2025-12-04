<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('name');                    // Darbo / pozicijos pavadinimas
            $table->decimal('quantity', 10, 2)->nullable(); // Kiekis
            $table->string('unit', 50)->nullable();    // vnt, m, tšk. ir t.t.
            $table->decimal('unit_price', 10, 2)->nullable(); // Kaina už vnt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_items');
    }
};
