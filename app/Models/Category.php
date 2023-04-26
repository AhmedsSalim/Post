<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Translatable;
use App\Models\CategoryTranslation;
use App\Models\Product;


class Category extends Model
{
    use HasFactory;
    use Translatable;
    public $guarded = [];
    public $translatedAttributes = ['name'];

    public function  products(){
        return $this->hasMany(Product::class);
    }

}
