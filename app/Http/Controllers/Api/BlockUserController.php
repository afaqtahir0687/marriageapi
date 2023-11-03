<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\BlockedUser;
use Illuminate\Http\Request;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlockUserController extends Controller
{
    public function getAllBlocked(Request $request)
    {
       
        try {
            $user_id = $request->id;
            $users = BlockedUser::with('user')->where('user_id', $user_id)->get();
            
            $blocked_users = array();
            if(!is_null($users))
            {
                foreach($users as $item)
                {
                    $item->user->profile_pic = url('images/profile_picture_folder/' . $item->user->profile_pic);
                    $blocked_users[] = $item->user;
                }
            }
            return response()->json([
                'status' => true,
                'data' => $blocked_users
            ], 200);

        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function markBlock(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'block_id' => 'required',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }

            $user = new BlockedUser;
            $user->user_id = $request->user_id;
            $user->block_id = $request->block_id;
            $user->save();

            return response()->json([
                'status' => true,
                'message'=>'User mark Blocked.'
           ], 200);
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function removeBlock(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(),
            [
                'user_id' => 'required',
                'block_id' => 'required',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }
            $user = BlockedUser::where('user_id',$request->user_id)->where('block_id',$request->block_id)->first();
            
            $user->delete();
            
            return response()->json([
                'status' => true,
                'message'=>'User unblocked.'
           ], 200);
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
?>
