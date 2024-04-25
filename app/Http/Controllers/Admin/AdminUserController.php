<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
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
    // public function sortField(Request $request)
    // { 
    //     $filters = [];
    //     $keyword = null;
    //     if (!empty($request->status)) {
    //         $status = $request->status;
    //         if ($status == 'active') {
    //             $status = 1;
    //         } else {
    //             $status = 0;
    //         }
    //         $filters[] = ['users.status', '=', $status];
    //     }

    //     if (!empty($request->group_id)) {
    //         $groupId = $request->group_id;

    //         $filters[] = ['users.status', '=', $groupId];
    //     }


    //     if (!empty($request->keyword)) {
    //         $keyword = $request->keyword;
    //     }

    //     $sortBy = $request->input('sort-by');
    //     $sortType = $request->input('sort-type') ? $request->input('sort-type') : 'asc';
    //     $allowSort = ['asc', 'desc'];

    //     if (!empty($sortType) && in_array($sortType, $allowSort)) {
    //         if ($sortType == 'desc') {
    //             $sortType = 'asc';
    //         } else {
    //             $sortType = 'desc';
    //         }
    //     } else {
    //         $sortType = 'asc';
    //     }

    //     $sortArr = [
    //         'sortBy' => $sortBy,
    //         'sortType' => $sortType
    //     ];
    //     $userAll = $this->users->getAllUsers($sortType, $keyword);
    //     return view('admin/user/admin-user', compact('userAll'));

    // }

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
