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
      Schema::create('work_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();             // PVZ.: ROZ-MONT, SKYD-MOD
            $table->string('name');                       // PVZ.: Dėžutės skylės gipse
            $table->string('category');                   // PVZ.: rozetes, apsvietimas, skydai
            $table->string('default_unit')->nullable();   // vnt, m, mod
            $table->decimal('default_price', 10, 2);      // kaina už vienetą
            $table->string('price_type')->default('simple'); // simple | fixed | per_module
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_catalog');
    }
};
