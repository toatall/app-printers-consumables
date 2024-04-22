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
        Schema::create('consumables_counts', function (Blueprint $table) {
            $table->id();
            $table->integer('id_consumable')->index();           
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();
            
            $table->foreign('id_consumable')->references('id')->on('consumables')->cascadeOnDelete();
        });

        Schema::create('consumables_counts_organizations', function(Blueprint $table) {
            $table->id();
            $table->integer('id_consumable_count')->index();
            $table->string('org_code', 5)->index();
            
            $table->unique(['id_consumable_count', 'org_code']);
            $table->foreign('id_consumable_count')->references('id')->on('consumables_counts')->cascadeOnDelete();
            $table->foreign('org_code')->references('code')->on('organizations')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumables_counts_organizations');
        Schema::dropIfExists('consumables_counts');
    }
};
