<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('description')->nullable()->after('name');
            $table->json('tags')->nullable()->after('description');
        });
    }
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['description', 'tags']);
        });
    }
};