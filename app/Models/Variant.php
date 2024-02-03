<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
class Variant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];
}
