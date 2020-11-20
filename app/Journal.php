<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'date_income','client_id','room_id', 'date_export'
    ];

    public function clients() {
        return $this->belongsTo('App\Client');
    }

    public function rooms() {
        return $this->belongsTo('App\Room');
    }

}
