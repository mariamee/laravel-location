<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'particulier_id',
        'categorie',
        'marque',
        'prix',
        'ville',
        'image',
        'title',
        'description',
        'date_debut',
        'date_fin',
        'disponibilite',
        'status'
    ];
}
