<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisPartenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'avis_id',
        'client_id',
        'partenaire_id',
    ];
}
