<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Package extends Model
{

    protected $table = 'packages';
    protected $primaryKey = 'package_id';


    public function users(){
        return $this->belongsTo('App\User'); 
    }



}
