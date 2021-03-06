<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("annonce_id");
            $table->foreign("annonce_id")->references("id")->on("annonces");
            $table->unsignedBigInteger("client_id");
            $table->foreign("client_id")->references("id")->on("users");
            $table->date('date_debut');
            $table->date('date_fin');
            $table->timestamp('date_acceptation')->nullable()
                ->useCurrent()->useCurrentOnUpdate();
            $table->string('status');
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
        Schema::dropIfExists('reservations');
    }
}
