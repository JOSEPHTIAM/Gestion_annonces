<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            // Informations sur l'acteur
            $table->string('user_nom');
            $table->string('user_role'); // client ou agent

            // Informations sur le service
            $table->string('service_nom');
            $table->decimal('montant_total', 15, 2);
            $table->string('ville_localisation');

            // L'attribut clé que tu as demandé
            $table->string('statut_paiement')->default('Payement non effectué');
            // 'Payement effectué' ou 'Payement non effectué'

            $table->string('reference_transaction')->nullable(); // ID unique ou email
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dashboards');
    }
};
