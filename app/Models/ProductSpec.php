<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{
    use HasFactory;

    protected $table = 'product_spec';

    protected $fillable = [
        'product_id',
        'product_spec_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ProductSpecCategory::class, 'product_spec_category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class); // Assumes you have a Product model
    }
}
