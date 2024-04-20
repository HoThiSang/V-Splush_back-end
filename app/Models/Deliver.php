<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliver extends Model
{
    use HasFactory;
    protected $table = 'delivers';

    protected $fillable = [
        'deliver_name',
        'email',
        'password',
    ];

    public function orders(){
        return $this->hasMany('App\Models\Order', 'deliver_id','id');
    }
}
