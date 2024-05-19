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
     *     tags={"Product"},
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
     *     @OA\Response(response="404", description="Contact not found")
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
    /**
     * @OA\Delete(
     *     path="/api/delete-contact/{id}",
     *     summary="Delete a contact by ID",
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the contact to delete",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Contact deleted successfully"),
     *     @OA\Response(response="404", description="Contact not found"),
     *     @OA\Response(response="400", description="ID is required"),
     *     @OA\Response(response="500", description="Failed to delete contact")
     * )
     */
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
