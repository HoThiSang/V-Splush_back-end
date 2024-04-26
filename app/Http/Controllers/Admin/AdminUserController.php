<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
               'status' =>'success',
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
