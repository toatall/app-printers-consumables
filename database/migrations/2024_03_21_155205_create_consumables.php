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
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->index();
            $table->string('name');
            $table->string('color')->nullable(true);            
            $table->text('description')->nullable(true);
            $table->integer('id_author');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_author')->references('id')->on('users');
        });

        Schema::create('printers_consumables', function (Blueprint $table) {
            $table->id();
            $table->integer('id_printer')->index();
            $table->integer('id_consumable')->index();
            $table->integer('id_author');
            $table->timestamps();
            
            $table->unique(['id_printer', 'id_consumable']);
            $table->foreign('id_printer')->references('id')->on('printers')->cascadeOnDelete();
            $table->foreign('id_consumable')->references('id')->on('consumables')->cascadeOnDelete();
            $table->foreign('id_author')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers_consumables');
        Schema::dropIfExists('consumables');
    }
};
