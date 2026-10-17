<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $fillable = [
        'user_nom', 'user_role', 'service_nom',
        'montant_total', 'ville_localisation',
        'statut_paiement', 'reference_transaction'
    ];
}
