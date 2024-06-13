<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orders;
    public function __construct()
    {
        $this->orders = new Order();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->check()) {
            $user = Auth()->user();
            $user_id = $user->id;
            $orders = $this->orders->getAllOrderByUserId($user_id);
            return response()->json([
                'status' => 'success',
                'data' => $orders
            ]);
        }
        return response()->json([
            'status' => 'error',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $order_code)
    {
        $order = $this->orders->getOrderById($order_code);
        if (!empty($order)) {
            $dataUpdate = [
                'order_status' => 'Cancel',
                'updated_at' => now()
            ];
            $order = $this->orders->updateStatusOrderByOrrderCode($order_code, $dataUpdate);
            if ($order) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'update status order successfully',
                    'data' => 200
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Update status prder not successfully',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'This order does not exist'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $order = $this->orders->findOrFail($id);

    //     try {
    //         $order->order_status = 'Cancelled';
    //         $order->save();
    //         return response()->json([
    //             'message' => 'Order status updated to Cancelled'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Failed to update order status'
    //         ], 500);
    //     }
    // }
    public function destroy(string $id)
    {
        $order = $this->orders->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        try {
            $order->order_status = 'Cancelled';
            $order->save();
            return response()->json([
                'message' => 'Order status updated to Cancelled'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update order status'
            ], 500);
        }
    }
}
