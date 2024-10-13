<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'driver_id',
        'programme_id',
        'photo',
        'price',
        'status',
        'number',
    ];

    public function guide(){
        return $this->belongsTo(Guide::class);
    }
    public function driver(){
        return $this->belongsTo(Driver::class);
    }
    public function programme(){
        return $this->belongsTo(Programme::class);
    }
    public function tourists(){
        //The relation between Tour & Tourist is 1 -> n
        return $this->hasMany(Tourist::class);
    }

    public function touristCount(){
        $this->number = $this->tourists()->count();
        $this->save();
    }
}
