<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'fName',
        'lName',
        'phoneNumber',
        'plateNumber',
        'description',
    ];

    public function tours(){
        //The relation between Driver & Tours is 1 -> n
        return $this->hasMany(Tour::class);
    }
}
