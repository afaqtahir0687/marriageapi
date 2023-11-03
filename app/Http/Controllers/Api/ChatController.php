<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\ChatThread;
use App\Models\Chat;
use App\Models\BlockedUser;
use Illuminate\Http\Request;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Cache;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function createText(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'other_user_id' => 'required',
                'text' => 'required',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }
            $user = User::find($request->user_id);
            if($user)
            {
                $other_user = User::find($request->other_user_id);
                if($other_user)
                {
                    $thread = ChatThread::where(['user_id'=>$request->user_id,'other_user_id'=>$request->other_user_id])->first();
                    if(is_null($thread))
                    {
                        $thread_other = ChatThread::where(['user_id'=>$request->other_user_id,'other_user_id'=>$request->user_id])->first();
                        if(is_null($thread_other))
                        {
                            $thread = new ChatThread;
                            $thread->user_id = $request->user_id;
                            $thread->other_user_id = $request->other_user_id;
                            thestaffhub353*         $thread->save();
                        }
                        else
                        {
                            $thread = $thread_other;
                        }
                    }
                    $chat = new Chat;
                    $chat->thread_id = $thread->id;
                    $chat->user_id = $request->user_id;
                    $chat->text = $request->text;
                    $chat->save();
                    return response()->json([
                        'status' => true,
                        'data'   => array('thread_id'=>$thread->id)
                ], 200);
                }
                else
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile Record not found.',
                    ], 200);
                }
            }
            else
            {
                return response()->json([
                        'status' => true,
                        'message' => 'User Record not found.',
                ], 200);
            }
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createImage(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'other_user_id' => 'required',
                'image' => 'required|image',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }

            $user = User::find($request->user_id);
            if($user)
            {
                $other_user = User::find($request->other_user_id);
                if($other_user)
                {
                    $thread = ChatThread::where(['user_id'=>$request->user_id,'other_user_id'=>$request->other_user_id])->first();
                    if(is_null($thread))
                    {
                        thestaffhub353*     $thread_other = ChatThread::where(['user_id'=>$request->other_user_id,'other_user_id'=>$request->user_id])->first();
                        if(is_null($thread_other))
                        {
                            $thread = new ChatThread;
                            $thread->user_id = $request->user_id;
                            $thread->other_user_id = $request->other_user_id;
                            $thread->save();
                        }
                        else
                        {
                            $thread = $thread_other;
                        }
                    }

                    if ($request->hasFile('image')) {
                        $image = $request->file('image');
                        $filename = time() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('chats/images'), $filename);
                    }

                    $chat = new Chat;
                    $chat->thread_id = $thread->id;
                    $chat->user_id = $request->user_id;
                    $chat->image = $filename;
                    $chat->save();
                    return response()->json([
                        'status' => true,
                        'message'=>'Image Send'
                ], 200);
                }
                else
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile Record not found.',
                    ], 200);
                }
            }
            else
            {
                return response()->json([
                        'status' => true,
                        'message' => 'User Record not found.',
                ], 200);
            }
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createVideo(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'other_user_id' => 'required',
                'video' => 'required|mimes:mov,mp4',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }
            $user = User::find($request->user_id);
            if($user)
            {
                $other_user = User::find($request->other_user_id);
                if($other_user)
                {
                    $thread = ChatThread::where(['user_id'=>$request->user_id,'other_user_id'=>$request->other_user_id])->first();
                    if(is_null($thread))
                    {
                        $thread_other = ChatThread::where(['user_id'=>$request->other_user_id,'other_user_id'=>$request->user_id])->first();
                        if(is_null($thread_other))
                        {
                            $thread = new ChatThread;
                            $thread->user_id = $request->user_id;
                            $thread->other_user_id = $request->other_user_id;
                            $thread->save();
                        }
                        else
                        {
                            $thread = $thread_other;
                        }
                    }
                    if ($request->hasFile('video')) {
                        $video = $request->file('video');
                        $filename = time() . '.' . $video->getClientOriginalExtension();
                        $video->move(public_path('chats/videos'), $filename);
                    }

                    $chat = new Chat;
                    $chat->thread_id = $thread->id;
                    $chat->user_id = $request->user_id;
                    $chat->video = $filename;
                    $chat->save();
                    return response()->json([
                        'status' => true,
                        'message'=>'Video Send'
                ], 200);
                }
                else
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile Record not found.',
                    ], 200);
                }
            }
            else
            {
                return response()->json([
                        'status' => true,
                        'message' => 'User Record not found.',
                ], 200);
            }
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function createAudio(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'other_user_id' => 'required',
                'audio' => 'required|mimes:mp3',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }
            $user = User::find($request->user_id);
            if($user)
            {
                $other_user = User::find($request->other_user_id);
                if($other_user)
                {
                    $thread = ChatThread::where(['user_id'=>$request->user_id,'other_user_id'=>$request->other_user_id])->first();
                    if(is_null($thread))
                    {
                        $thread_other = ChatThread::where(['user_id'=>$request->other_user_id,'other_user_id'=>$request->user_id])->first();
                        if(is_null($thread_other))
                        {
                            $thread = new ChatThread;
                            $thread->user_id = $request->user_id;
                            $thread->other_user_id = $request->other_user_id;
                            $thread->save();
                        }
                        else
                        {
                            $thread = $thread_other;
                        }
                    }
                    if ($request->hasFile('audio')) {
                        $audio = $request->file('audio');
                        $filename = time() . '.' . $audio->getClientOriginalExtension();
                        $audio->move(public_path('chats/audios'), $filename);
                    }

                    $chat = new Chat;
                    $chat->thread_id = $thread->id;
                    $chat->user_id = $request->user_id;
                    $chat->audio = $filename;
                    $chat->save();
                    return response()->json([
                        'status' => true,
                        'message'=>'Audio Send'
                ], 200);
                }
                else
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Profile Record not found.',
                    ], 200);
                }
            }
            else
            {
                return response()->json([
                        'status' => true,
                        'message' => 'User Record not found.',
                ], 200);
            }
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function getAllChats(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(),
            [
                'user_id' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }
            $user = User::find($request->user_id);
            if($user)
            {
                $chat = ChatThread::select('id','other_user_id')->where(['user_id'=>$request->user_id])->orWhere(['other_user_id'=>$request->user_id])->with('user','last_sms')->get();
                $chats = array();
                if(!is_null($chat))
                {
                    foreach($chat as $item)
                    {
                        if(!is_null($item->user) && !is_null($item->last_sms))
                        {
                            $sms = $item->last_sms->first();
                            $dt = Carbon::parse($sms->created_at); $time = $dt->diffForHumans();
                            $chats[] =  array(
                                'thread_id' => $item->id,
                                'user_id' => $item->user->id,
                                'name' => $item->user->name,
                                'profile_pic' => url('images/profile_picture_folder/'.$item->user->profile_pic),
                                'online' => Cache::has('is_online' . $item->user->id),
                                'sms' => array('text'=>$sms->text,'time'=>$time)
                            );
                        }
                    }
                }
                return response()->json([
                    'status' => true,
                    'data'=> $chats
                ], 200);
            }
            else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'User Record not found.',
                ], 200);
            }
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function getChat($id)
    {
        try
        {
            $chat = ChatThread::find($id);
            if($chat)
            {
                $chat = Chat::where(['thread_id'=>$id])->with('user')->get();
                $chats = array();
                if(!is_null($chat))
                {
                    foreach($chat as $item)
                    {
                        if(!is_null($item->user))
                        {
                            $dt = Carbon::parse($item->created_at); $time = $dt->diffForHumans();
                            $chats[] =  array(
                                'profile_pic' => url('images/profile_picture_folder/'.$item->user->profile_pic),
                                'text' => $item->text,
                                'image' => isset($item->image) ? url('chats/images/'.$item->image) : null,
                                'video' => isset($item->video) ? url('chats/videos/'.$item->video) : null,
                                'audio' => isset($item->audio) ? url('chats/audios/'.$item->audio) : null,
                                'time' => $time
                            );

                        }
                    }
                }
                return response()->json([
                    'status' => true,
                    'data'=> $chats
                ], 200);
            }
            else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Chat not found.',
                ], 200);
            }
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
