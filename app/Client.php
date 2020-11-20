<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $fillable = [
        'surname', 'name', 'lastname', 'mail', 'comment'
    ];

    public function journal() {
        return $this->hasMany('App\Journal');
    }
}
