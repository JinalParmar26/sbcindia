<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecOption extends Model
{
    use HasFactory;

    protected $table = 'product_spec_option';

    protected $fillable = [
        'spec_category',
        'cat_option',
        'option_val',
    ];

    public function category()
    {
        return $this->belongsTo(ProductSpecCategory::class, 'spec_category');
    }
}
