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

       /**
     * @OA\Get(
     *     path="/api/admin-show-all-orders",
     *     summary="Get all orders",
     *     tags={"Order"},
     *     @OA\Response(response="200", description="Success"),
     *
     * )
     */
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


        /**
     * @OA\Get(
     *     path="/api/admin-show-detail-order/{id}",
     *     summary="Detail a order by ID",
     *     tags={"Order"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to detail",
     *    @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Order not found")
     * )
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
                    'data' => $orderDetail
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed show detail order',
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
      /**
     * @OA\Post(
     *     path="/api/admin-update-status-order/{id}",
     *     summary="Update a status order by ID",
     *     tags={"Order"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to update",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="order data",
     *         @OA\JsonContent(
     *             required={"order_status"},
     *             @OA\Property(property="order_status", type="string", example="ordered")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="order not found"),
     *     @OA\Response(response="500", description="Internal Server Error")
     * )
     */
    public function update(Request $request, string $id)
    {
        //
        if ($request->isMethod('post')) {
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
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The method not put',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
