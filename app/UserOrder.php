<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference', 'pagseguro_code', 'pagseguro_status', 'items', 'store_id'
    ];

    /**
     * Get a single User
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a single store
     *
     * @return Store
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
