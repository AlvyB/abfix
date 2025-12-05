<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('room_items', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('unit_price');
            $table->text('comment')->nullable()->after('is_completed');
        });
    }

    public function down(): void
    {
        Schema::table('room_items', function (Blueprint $table) {
            $table->dropColumn(['is_completed', 'comment']);
        });
    }
};
