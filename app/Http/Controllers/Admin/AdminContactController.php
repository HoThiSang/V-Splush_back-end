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
    /**
     * @OA\Get(
     *     path="/api/admin-contact",
     *     summary="Get all contacts",
     *     tags={"Contacts"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index()
    {
        $contactAll = $this->contact->getAllContact();
        return $contactAll;
    }
    /**
     * @OA\Get(
     *     path="/api/admin-view-contact/{id}",
     *     summary="Detail a contact by ID",
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the contact to detail",
     *    @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Comment not found")
     * )
     */
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
    public function destroy($id)
    {
        if (!empty($id)) {
            $cart = $this->contact->getContactById($id);
            if (!empty($cart)) {
                $cartDelete = $this->contact->deleteContactId($id);
                if ($cartDelete) {
                    return response()->json(['success' => 'Deleted contact successfully!'], 200);
                }
                return response()->json(['error' => 'Failed to delete contact!'], 500);
            }
            return response()->json(['error' => 'Not found contact with id : ' . $id], 404);
        } else {
            return response()->json(['error' => 'ID is required'], 400);
        }
    }
}
