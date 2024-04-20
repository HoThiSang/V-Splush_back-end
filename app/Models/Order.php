<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'name', 'address', 'phone_number', 'payment_method', 'deleted_at'
    ];
    
    public function orderItems(){
        return $this->hasMany('App\Models\OrderItem', 'order_id','id');
    }

    public function deliver(){
        return $this->belongsTo('App\Models\Deliver', 'deliver_id','id');
    }
}
