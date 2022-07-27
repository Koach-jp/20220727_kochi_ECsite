<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function order_details() {
        return $this->hasMany('App\Models\OrderDetail','order_uuid','uuid');
    }
}
