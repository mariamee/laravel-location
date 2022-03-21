<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnoncesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("particulier_id");
            $table->foreign("particulier_id")->references("id")->on("users");

            // $table->unsignedBigInteger("objet_id");
            // $table->foreign("objet_id")->references("id")->on("objets");
            $table->string('categorie', 50);
            $table->string('marque', 50);
            $table->float('prix', 10, 0);

            $table->string("ville");
            $table->string('title', 50);
            $table->string('description');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->timestamp('date_pub')->useCurrent()->useCurrentOnUpdate()->nullable();
            $table->boolean('disponibilite');
            $table->boolean('status');
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
        Schema::dropIfExists('annonces');
    }
}
