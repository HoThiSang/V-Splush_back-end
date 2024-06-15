<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    protected $users;


    public function __construct()
    {
        $this->users = new User();
        // $this->middleware('alreadyLoggedIn');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $allUsers = $this->users->getAllUsers();

        if (!empty($allUsers)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Users retrieved successfully',
                'data' => $allUsers
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve users',
            ], 500);
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $newStatus = $user->status === 'Enabled' ? 'Disabled' : 'Enabled';

        if ($newStatus === 'Disabled') {
            $order = Order::where('user_id', $user->id)->first();
            if (!$order || $order->status === 'delivered') {
                $user->status = 'Disabled';
                $user->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'User disabled successfully'
                ]);
            } else {
                return response()->json(['error' => 'Cannot disable user. Order not delivered'], 400);
            }
        } else {
            $user->status = 'Enabled';
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'User enabled successfully'
            ]);
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
