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
        Schema::create('users_organizations', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('org_code');
            $table->timestamps();

            $table->unique(['id_user', 'org_code']);
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('org_code')->references('code')->on('organizations')->cascadeOnDelete()->cascadeOnUpdate();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_organizations');
    }
};
