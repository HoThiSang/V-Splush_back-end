<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = ['email', 'name', 'subject','contact_status'];
    public function creatNewContact($data)
    {
        return DB::table($this->table)->insert($data);
    }
    public function getContactById($id)
    {
        return DB::table($this->table)->where('id', $id)->first();
     
    }

}
