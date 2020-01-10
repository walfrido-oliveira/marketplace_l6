<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'body', 'price', 'slug'
    ];

    /**
     * return a single store
     *
     * @return Store
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * gets categories
     *
     * @return Collection
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * gets photos
     *
     * @return Collection
     */
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
