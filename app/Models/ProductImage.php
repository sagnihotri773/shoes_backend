<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'images'];

    protected $casts = [
        'images' => 'json', // Cast the images attribute to JSON
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function coverImage()
    {
        return $this->id;
    }


}   