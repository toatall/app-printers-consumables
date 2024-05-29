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
        Schema::create('organizations', function (Blueprint $table) {
            $table->string('code', 5)->primary();
            $table->string('name', 200);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['code', 'name']);
        });

        // Schema::table('users', function(Blueprint $table) {
        //     $table->foreign('org_code')->references('code')->on('organizations')->cascadeOnUpdate();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('users', function(Blueprint $table) {
        //     $table->dropForeignIdFor(App\Models\Auth\User::class, 'org_code');
        // });

        Schema::dropIfExists('organizations');
    }
};
