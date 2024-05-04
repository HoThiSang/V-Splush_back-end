<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $orders;
    public function __construct()
    {
        $this->orders = new Order();
    }
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            $orders = $this->orders->getAllOrders();
            if ($orders) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Show all orders successfully',
                    'data' => $orders
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed show all orders',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The method not get',
            ], 405);
        }
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
        if (!empty($id)) {
            $orderDetail = $this->orders->getOrderById($id);
            if (!empty($orderDetail)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Show detail order successfully',
                    'data' => $orderDetail, 200
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed show detail comment',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'This order does not exist',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        if ($request->isMethod('put')) {
            $order = $this->orders->getOrderById($id);
            if (!empty($order)) {
                $dataUpdate = [
                    'order_status' => $request->order_status,
                    'updated_at' => now()
                ];
                $order = $this->orders->updateStatusOrder($id, $dataUpdate);
                if ($order) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'update status order successfully',
                        'data' => $order, 200
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
        return response()->json([
            'status' => 'error',
            'message' => 'The method not put',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
