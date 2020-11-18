<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
   protected $table = 'wish_list';
   protected $fillable = ['product_name'];
}
