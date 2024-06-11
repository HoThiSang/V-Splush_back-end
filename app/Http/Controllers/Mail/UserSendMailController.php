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
    /**
     * @OA\Post(
     *     path="/api/user-send-contact",
     *     tags={"Contacts"},
     *     description="Send an email",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Sender's name",
     *         @OA\Schema(type="string", example="John Doe")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         description="Sender's email address",
     *         @OA\Schema(type="string", format="email", example="john@example.com")
     *     ),
     *     @OA\Parameter(
     *         name="subject",
     *         in="query",
     *         required=true,
     *         description="Email subject",
     *         @OA\Schema(type="string", example="Regarding your inquiry")
     *     ),
     *     @OA\Parameter(
     *         name="message",
     *         in="query",
     *         required=true,
     *         description="Email message content",
     *         @OA\Schema(type="string", example="Dear Support Team, ...")
     *     ),
     *     @OA\Response(response="200", description="Email sent successfully"),
     *     @OA\Response(response="400", description="Validation error"),
     *     @OA\Response(response="500", description="Failed to send email")
     * )
     */

    public function sendEmail(Request $request)
    {
        if (Auth()->check()) {
            $user = Auth()->user();
            $user_id = $user->id;
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|',
                'subject' => 'required',
                'message' => 'required',
            ]);
            $data = [
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'name' => $request->name,
                'user_id' => $user_id,
                'contact_status' => 'No contact yet',
                'created_at' => now(),
            ];
            try {
                Mail::to(getenv('MAIL_USERNAME'))->send(new UserSendMail($data));
                $this->contact->creatNewContact($data);
                return response()->json([
                    'status' => 'success',
                    'message' => 'The email has been successfully sent to the system',
                    'data' => $data
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'sucssce',
                    'message' => 'Send the email failed! ' . $e->getMessage()
                ], 500);
            }
        }
    }
    // return response()->json([
    //     'message' => 'You must to login'
    // ], 401);}

    /**
     * @OA\Post(
     *     path="/api/admin-reply-contact/{id}",
     *     tags={"Contacts"},
     *     summary="Reply to an email by ID",
     *     description="Reply to an email by ID with a message",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the contact to reply to",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Email reply details",
     *         @OA\JsonContent(
     *             required={"message"},
     *             @OA\Property(property="message", type="string", example="Your message here", description="Email reply message content")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Email reply sent successfully"),
     *     @OA\Response(response="400", description="Validation error"),
     *     @OA\Response(response="404", description="Contact not found"),
     *     @OA\Response(response="405", description="Invalid request method"),
     *     @OA\Response(response="500", description="Failed to send email reply")
     * )
     */

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
