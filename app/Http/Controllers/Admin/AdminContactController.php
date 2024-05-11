<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class AdminContactController extends Controller
{
    Protected $contact;
    public function __construct()
    {
        $this->contact = new Contact();
    }
    public function index()
    {
        $contactAll = $this->contact->getAllContact();
        return  $contactAll;
    }
    public function show($id)
    {
        if (!empty($id)) {
            $cart = $this->contact->getContactById($id);
            if (!empty($cart)) {
                return response()->json(['cart' => $cart], 200);
            } else {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Not found contact with id : ' . $id
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'ID is required'
            ], 400);
        }
    }
}
