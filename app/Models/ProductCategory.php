<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;
    
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
        'name', 'type', 'model'
    ];
    

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

}
