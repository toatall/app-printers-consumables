<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesMain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('org_code')->nullable(false)->default('8600');
            $table->string('vendor', 100)->nullable(false);
            $table->string('model', 200)->nullable(false);
            $table->boolean('color_print')->default(false);           
            $table->unique(['vendor', 'model']);
            $table->timestamps();
        });

        Schema::create('printers_consumables', function(Blueprint $table) {
            $table->id();           
            $table->integer('id_printer')->index();
            $table->boolean('arch')->default(false);
            $table->string('type_consumable', 30)->nullable(false);
            $table->string('name');
            $table->string('color')->nullable(true);            
            $table->text('description')->nullable(true);
            $table->unsignedInteger('count_local')->default(0)->nullable(false);   
            $table->unsignedInteger('count_stock')->default(0)->nullable(false);           
            $table->timestamps();
            
            $table->foreign('id_printer')->references('id')->on('printers');
            $table->unique(['id_printer', 'name']);
        });

        Schema::create('printers_consumables_move', function(Blueprint $table) {
            $table->id();
            $table->integer('id_printer_consumables')->index();
            $table->integer('id_user')->index();
            $table->string('type_move', 30)->nullable(false);
            $table->integer('count_local');
            $table->integer('count_stock');
            $table->string('description', 2000)->nullable(true);
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->foreign('id_printer_consumables')->references('id')->on('printers_consumables');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printers_consumables_move');        
        Schema::dropIfExists('printers_consumables');
        Schema::dropIfExists('printers');
    }
}
