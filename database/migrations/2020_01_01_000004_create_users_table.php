<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('name');
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();            
            $table->string('photo_path', 100)->nullable();

            $table->string('domain', 50)->default('local');
            $table->string('org_code', 5);
            $table->string('company', 500)->nullable();
            $table->string('fio', 250)->nullable();
            $table->string('department', 250)->nullable();
            $table->string('post', 250)->nullable();
            $table->string('telephone', 100)->nullable();
            $table->string('lotus_mail', 100)->nullable();
            $table->jsonb('members')->default('{}');
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
