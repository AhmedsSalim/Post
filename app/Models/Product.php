<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Astrotomic\Translatable\Translatable;


class Product extends Model
{
    use HasFactory;

    use Translatable;
    public $guarded = [];
    public $translatedAttributes = ['name','description'];
    protected $appends=['image_path','profit_percent'];


    public function getImagePathAttribute()
    {
        return asset('uploads/product_images/' . $this->image);

    }//end of get image path

    public function category(){
        return $this->belongsTo(Category::class);
    }//end of get category

    public function getProfitPercentAttribute()
    {
        $profit = $this->sale_price  -   $this->purchase_price  ;
        $purchase_price =  $profit * 100 /  $this->purchase_price  ;
        return number_format($purchase_price ,2);

    }//end of get ProfitPercentAttribute

    /**
     * The roles that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_order',);
    }

}
