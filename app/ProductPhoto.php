<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProductPhoto extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image'
    ];

    /**
     * Get product
     *
     * @return Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
