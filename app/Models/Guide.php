<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        'fName',
        'lName',
        'address',
        'mobile',
        'description',
    ];

    public function tours(){
        //The relation between Guide & Tours is 1 -> n
        return $this->hasMany(Tour::class);
    }
}
