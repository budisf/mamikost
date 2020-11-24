<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    protected $table = 'kost';

     protected $fillable = [
        'name', 'price', 'location','availability', 'owner_id','desc',
    ];
    
}
