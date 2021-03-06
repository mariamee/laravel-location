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
            $table->id();
            $table->string('name');
            // $table->string('nom', 30);
            // $table->string('prenom', 30);
            $table->string('ville', 30);
            $table->string('addresse', 50);
            $table->string('cin', 15);
            $table->string('telephone', 20);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->String('photo')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
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
