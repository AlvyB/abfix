<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('legal_type')->default('company');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('vat')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('director')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
