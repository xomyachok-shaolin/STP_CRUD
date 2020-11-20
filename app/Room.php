<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = [
        'number', 'capacity', 'comfortable', 'price'
    ];

    public function journal() {
        return $this->hasMany('App\Journal');
    }
}

