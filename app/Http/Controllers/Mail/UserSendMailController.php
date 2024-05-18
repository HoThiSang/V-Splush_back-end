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
    protected $contacts;

    public function __construct()
    {
        $this->contacts = new Contact();
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
            $this->contacts->creatNewContact($data);
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
        // if(Auth()->user()){
        if ($request->isMethod('post')) {
            $cart = $this->contacts->getContactById($id);
            if (!empty($cart)) {
                $request->validate([
                    'message' => 'required',
                ]);
                $dataSend = [
                    'email' => $cart->email,
                    'subject' => $cart->subject,
                    'name' => $cart->name,
                    'message'=>$request->message,
                    'contact_status'=> 'Contacted',
                    'updated_at'=>now()
                ];
                $cartdata= [
                    'status' => 'Contacted',
                    'user_id' => Auth()->user()->id,
                    'updated_at' => now()
                ];
                Mail::to($cart->email)->send(new AdminReplyMail($dataSend));
                if (Mail::failures()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Email sending failed'
                    ], 500);
                }
                $this->contacts->updateContact($id, $cartdata);
                return response()->json([
                    'success' => true, 
                    'message' => 'The email has been successfully sent to the system'
                ], 200);
            }
        }
        return response()->json([
            'success' => true, 
            'message' => 'Email sending failed'
        ], 500);
    // }
    }
}
