<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Product extends Model 
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $casts = [
        'product_image' => 'array',
    ];
    
}

