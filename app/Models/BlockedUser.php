<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'block_id', 'id')->select('id','name','email','profile_pic');
    }
}
