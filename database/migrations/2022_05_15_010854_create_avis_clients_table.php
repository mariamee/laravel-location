<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvisClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avis_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("avis_id");
            $table->foreign("avis_id")->references("id")->on("avis");
            $table->unsignedBigInteger("client_id");
            $table->foreign("client_id")->references("id")->on("users");
            $table->unsignedBigInteger("particulier_id");
            $table->foreign("particulier_id")->references("id")->on("users");
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
        Schema::dropIfExists('avis_clients');
    }
}
