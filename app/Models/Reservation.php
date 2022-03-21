<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'annonce_id',
        'client_id',
        'date_debut',
        'date_fin',
        'date_reservation',
    ];
}
