<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Mail\UserSendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Mail\ContactFormEmail;
use  Illuminate\Mail\PendingMail;
use App\Mail\AdminReplyMail;
use Illuminate\Support\Facades\Auth;

class UserSendMailController extends Controller
{
    protected $contact;

    public function __construct()
    {
        $this->contact = new Contact();
    }
    public function sendEmail(Request $request)
    {
        // if(Auth()->check()){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|',
            'subject' => 'required',
            'message' => 'required',
        ]);
        $data = [
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'name' => $request->input('name'),
            'user_id'=>2,
            'contact_status' => 'No contact yet',
            'created_at' => now(),
        ];
        try {
            Mail::to(getenv('MAIL_USERNAME'))->send(new UserSendMail($data));
            $this->contact->creatNewContact($data);
            return response()->json([
                'success' => true, 
                'message' => 'The email has been successfully sent to the system'
            ], 200);
        } 
        catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Send the email failed! ' . $e->getMessage()
            ], 500);
        }
    }
    // return response()->json([
    //     'message' => 'You must to login'
    // ], 401);}

    public function replyEmail(Request $request, $id)
{
    // if (Auth::check()) {
        if ($request->isMethod('post')) {
            $cart = $this->contact->getContactById($id);

            if (is_null($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contact not found'
                ], 404);
            }

            $dataSend = [
                'email' => $cart->email,
                'subject' => $cart->subject,
                'name' => $cart->name,
                'message' => $request->message,
                'contact_status' => 'Contacted',
                'updated_at' => now()
            ];

            $cartdata = [
                'contact_status' => 'Contacted',
                'user_id' => $cart->user_id,
                'updated_at' => now()
            ];

            try {
                Mail::to($cart->email)->send(new AdminReplyMail($dataSend));

                $this->contact->updateContact($id, $cartdata);

                return response()->json([
                    'success' => true,
                    'message' => 'The email has been successfully sent to the system'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request method'
            ], 405);
        }
    // } else {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Unauthorized'
    //     ], 403);
    // }
}
}
