<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $guarded=[];


     // user BelongsTo  oder

    public function client()
    {
        return $this->belongsTo(Client::class);
    }  // end user BelongsTo  oder

 /////  products  belongsToMany
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order')->withPivot('quantity');
    }  /////  end products  belongsToMany

}
