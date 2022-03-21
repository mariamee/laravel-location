<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisObjet extends Model
{
    use HasFactory;

    protected $fillable = [
        'annonce_id',
        'client_id',
        'avis_id',
    ];
}
