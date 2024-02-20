<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'user_id'; // Specify the primary key column
    public $incrementing = false; // Disable auto-incrementing primary key

    public function user(){
        return $this->belongsTo(User::class);
    }
}
