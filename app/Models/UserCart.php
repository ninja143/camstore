<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    
    // public $incrementing = false;
    // protected $keyType = 'string';
    // public $timestamps = false;
    // const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'updated_date';
    // protected $attributes = [
    //     'delayed' => false,
    // ];
    protected $dateFormat = 'U';
    protected $connection = 'mysql';
    protected $primaryKey   = 'id';
    protected $dates    = [ 'created_at', 'updated_at'];
    protected $hidden   = [];
    protected $casts = [
        // 'login_in_time' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
    protected $fillable = [
        'product_id', 'user_id', 'quantity', 'status'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
