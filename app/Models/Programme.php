<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'description',
    ];

    public function tours(){
        //The relation between Programme & Tours is 1 -> n
        return $this->hasMany(Tour::class);
    }
}
