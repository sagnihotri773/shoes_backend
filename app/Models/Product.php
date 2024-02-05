<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Variant;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'selling_price',
        'discount',
        'price',
        'is_feature'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasOne(ProductImage::class);
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'product_variants')->withPivot('quantity');
    }
}
