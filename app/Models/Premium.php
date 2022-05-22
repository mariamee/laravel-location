<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premium extends Model
{
    use HasFactory;

    public $table = "premiums";

    protected $fillable = [
        'annonce_id',
        'date_debut',
        'date_fin',
    ];
}
