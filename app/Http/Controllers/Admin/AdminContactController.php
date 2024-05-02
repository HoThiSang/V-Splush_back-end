<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class AdminContactController extends Controller

{
    protected $contact;
    public function __construct()
    {
        $this->contact = new Contact();
    }
    public function index()
    {
        $contactAll = $this->contact->getAllContact();
        return $contactAll;
    }

    public function show($id)
    {
        if (!empty($id)) {
            $cart = $this->contact->getContactById($id);
            if (!empty($cart)) {
                return response()->json(['cart' => $cart], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Not found contact with id : ' . $id], 404);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'ID is required'], 400);
        }
    }

    public function update(Request $request, $id)
    {
        if (!empty($id)) {
            $cart = $this->contact->getContactById($id);
            if (!empty($cart)) {
                $contactStatus = $request->input('contact_status');
                if (!is_null($contactStatus)) {
                    $updateData = ['contact_status' => $contactStatus];
                    $cartUpdate = $this->contact->updateContact($id, $updateData);
                    if ($cartUpdate) {
                        return response()->json([
                            'status' => 'success', 
                            'message' => 'Updated contact status successfully!'
                        ], 200);
                    }
                    return response()->json([
                        'status' => 'error', 
                        'message' => 'Failed to update contact status!'
                    ], 500);
                } else {
                    return response()->json([
                        'status' => 'error', 
                        'message' => 'Contact status cannot be null!'
                    ], 400);
                }
            }
            return response()->json([
                'status' => 'error', 
                'message' => 'Not found contact with id : ' . $id
            ], 404);
        } else {
            return response()->json([
                'status' => 'error', 
                'message' => 'ID is required'
            ], 400);
        }
    }
}
