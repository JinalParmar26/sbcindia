<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecCategory extends Model
{
    use HasFactory;

    protected $table = 'product_spec_category';

    protected $fillable = [
        'category',
        'is_dynamic',
    ];

    public function specs()
    {
        return $this->hasMany(ProductSpec::class, 'product_spec_category_id');
    }

    public function options()
    {
        return $this->hasMany(ProductSpecOption::class, 'spec_category');
    }

}
