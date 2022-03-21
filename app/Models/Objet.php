<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objet extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie',
        'marque',
        'prix',
        'partenaire_id',
    ];
}
