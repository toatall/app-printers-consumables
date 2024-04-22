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
        // добавление расходных материалов (поступление)
        Schema::create('consumables_counts_added', function (Blueprint $table) {
            $table->id();
            $table->integer('id_consumable_count')->index();  
            $table->integer('id_author');          
            $table->unsignedInteger('count');
            $table->timestamps();

            $table->foreign('id_consumable_count')->references('id')->on('consumables_counts')->cascadeOnDelete();
            $table->foreign('id_author')->references('id')->on('users');
        });

        // установленные расходные материалы
        Schema::create('consumables_counts_installed', function (Blueprint $table) {
            $table->id();
            $table->integer('id_consumable_count')->index();
            $table->integer('id_printer_workspace')->index();
            $table->integer('id_author');
            $table->unsignedInteger('count');
            $table->timestamps();

            $table->foreign('id_consumable_count')->references('id')->on('consumables_counts')->cascadeOnDelete();
            $table->foreign('id_printer_workspace')->references('id')->on('printers_workplace')->cascadeOnDelete();
            $table->foreign('id_author')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumables_counts_added');
        Schema::dropIfExists('consumables_counts_installed');
    }
};
