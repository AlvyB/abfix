<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('address')->nullable()->after('client_name');
            $table->date('due_date')->nullable()->after('notes');
            $table->string('contact_phone')->nullable()->after('due_date');
            $table->string('contact_email')->nullable()->after('contact_phone');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['address', 'due_date', 'contact_phone', 'contact_email']);
        });
    }
};
