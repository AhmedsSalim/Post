<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
     public $guarded = [];

     protected $casts = ['phone'=> 'array'];



     public function getNameAttribyte($value){
         return ucfirst($value);

     }//end of get name

        //Order Relations\HasMany  client

        public function orders()
        {
            return $this->hasMany(Order::class);
        }      // end Order Relations\HasMany  client



}
