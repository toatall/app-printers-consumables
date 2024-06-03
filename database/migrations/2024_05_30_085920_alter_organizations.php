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
        Schema::table('organizations', function(Blueprint $blueprint) {
            $blueprint->string('parent', 5)->nullable();
            $blueprint->foreign('parent')->references('code')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function(Blueprint $blueprint) {
            $blueprint->dropColumn('parent');
        });
    }
};
