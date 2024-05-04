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

   
}
