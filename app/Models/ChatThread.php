<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatThread extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class,'other_user_id','id')->select('id','name','profile_pic');
    }
    public function last_sms()
    {
        return $this->hasMany(Chat::class,'thread_id','id')->orderBy('id', 'DESC')->select('thread_id','text','created_at');
    }

}
