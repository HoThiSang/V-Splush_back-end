<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'name', 'address', 'phone_number', 'payment_method', 'deleted_at'
    ];

    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id', 'id');
    }

    public function deliver()
    {
        return $this->belongsTo('App\Models\Deliver', 'deliver_id', 'id');
    }

    public function getAllOrders()
    {
        return DB::table($this->table)->get();
    }

    public function getOrderById($id)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->first();
    }

    public function updateStatusOrder($id, $data)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }

    public function creatNewOrder($data)
    {
        return DB::table($this->table)->insertGetId($data);
    }

    public function getOrderByOrderCode($order_code)
    {
        return DB::table($this->table)
            ->where('order_code', $order_code)
            ->first();
    }

    public function updateStatusOrderByOrrderCode($order_code, $data)
    {
        return DB::table($this->table)
            ->where('order_code', $order_code)
            ->update($data);
    }

    public function getAllOrder()
    {
        return Order::join('users', 'users.id', '=', 'orders.user_id')
            ->whereNull('orders.deleted_at')
            ->select('orders.id', 'orders.address', 'orders.phone_number', 'orders.order_status', 'orders.total_price', 'orders.payment_method', 'orders.created_at', 'users.name', 'users.phone')
            ->get();
    }
    public function getAllOrderByUserId($user_id)
    {
        return Order::join('users', 'users.id', '=', 'orders.user_id')
            ->where('user_id', $user_id)
            ->whereNull('orders.deleted_at')
            ->select('orders.id', 'orders.address', 'orders.phone_number', 'orders.order_status','orders.order_code',  'orders.total_price', 'orders.payment_method', 'orders.created_at', 'users.name', 'users.phone')
            ->get();
    }
}
