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
        // основания таблица с принтерами
        // указывается производитель, модель принтера, и т.п.
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('vendor', 100);
            $table->string('model', 200);
            $table->boolean('is_color_print')->default(false);
            $table->unique(['vendor', 'model']);
            $table->integer('id_author');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_author')->references('id')->on('users');
        });

        // таблица с конкретными принтерами на рабочих местах
        // указывается код организации, кабинет, серийный номер и т.п.
        Schema::create('printers_workplace', function(Blueprint $table) {
            $table->id();
            $table->integer('id_printer')->index();
            $table->string('org_code', 5)->index();
            $table->string('location', 200);
            $table->string('serial_number', 50)->nullable();
            $table->string('inventory_number', 50);
            $table->integer('id_author');
            $table->timestamps();

            $table->unique(['org_code', 'inventory_number']);
            $table->foreign('id_printer')->references('id')->on('printers');
            $table->foreign('org_code')->references('code')->on('organizations')->cascadeOnUpdate();
            $table->foreign('id_author')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers_workplace');
        Schema::dropIfExists('printers');
    }
};
