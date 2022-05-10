<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionAdicional extends Model
{
    protected $table='informacion_adicional_refugio';
    protected $primarykey='id';
    protected $fillable=['Estado','Numero_Nucleos','NumeroHabitaciones','Refugio_id'];
    public $timestamps=true;
    use HasFactory;
}
