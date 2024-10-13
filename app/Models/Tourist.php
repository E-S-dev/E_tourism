<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tourist extends Model
{
    use HasFactory;

    protected $fillable = [
        'fName',
        'lName',
        'phoneNumber',
        'tour_id',
    ];


    public function tour(){
        return $this->belongsTo(Tour::class);
    }

    public function user(){
        return $this->hasOne(User::class);
    }
}
