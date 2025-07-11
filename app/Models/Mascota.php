<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Persona;

class Mascota extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'edad',
        'persona_id',
        'imagen'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
