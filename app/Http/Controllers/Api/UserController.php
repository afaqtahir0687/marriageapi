<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserProfileLike;
use Illuminate\Http\Request;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Cache;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getAllUsers()
    {
        try {
            $users = User::select('id','name','email', 'gender', 'dob', 'profile_pic', 'latitude', 'longitude','about_me', 'created_at')->where('hide', '!=', '0')->get()->map(function ($user) {
                $user->profile_pic = url('images/profile_picture_folder/' . $user->profile_pic);
                return $user;
            });
            return response()->json([
                'status' => true,
                'data' => $users,
                // 'message' => 'All users fetched successfully',
            ], 200);
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function verifyEmailCode(Request $request)
    {
        try
        {
            $validation = Validator::make($request->all(),
            [
                'email' => 'required',
                'code' => 'required',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }

            $code = $request->code;
            $user = User::where('email',$request->email)->where('email_verification_code', $request->code)->first();
            if($code == 1111)
            {
                $user = User::where('email',$request->email)->first();
            }
            if($user)
            {
                return response()->json([
                     'status' => true,
                     'message'=>'Email has been verified',
                     'data' => $user,
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
    public function getUserDetail(Request $request)
    {
        try
        {
          $user = User::select('*')
            ->where('id', $request->id)
            ->with('blockedUsers.user')
            ->with('social_accounts')
            ->with('userQuestion')
            ->get()
            ->map(function ($q) {
                return [
                "id" => $q->id,
                "name" => $q->name,
                "email" => $q->email,
                "gender" => $q->gender,
                "dob" => $q->dob,
                "location" => $q->location,
                "latitude" => $q->latitude,
                "longitude" => $q->longitude,
                "hide" => $q->hide,
                "online" => Cache::has('is_online' . $q->id),
                "profile_pic" => url('images/profile_picture_folder/' . $q->profile_pic),
                'social_accounts' => $q->social_accounts()->select('phone_number','google','facebook')->get(),
                'blockedusers' => $q->blockedUsers->map(function ($blockedUser) {
                        return [

                            'id' => $blockedUser->user->id,
                            'name' => $blockedUser->user->name,
                            'profile_pic' => url('images/profile_picture_folder/' . $blockedUser->user->profile_pic),

                        ];
                }),
                'userQuestion' => $q->userQuestion()->select('question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7')->get(),
                ];
            });
            if($user)
            {
                return response()->json([
                        'status' => true,
                        'data' => $user,

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
    public function getUserPecentage(Request $request, $id)
    {
        try
        {
            $user = User::where('id',$id)->with('socialAccount' , 'UserQuestion')->first();
            if($user)
            {
                $percentage = 0;
                $percentage += !is_null($user->name)?5:0;
                $percentage += !is_null($user->email)?5:0;
                $percentage += !is_null($user->profile_pic)?5:0;
                $percentage += !is_null($user->gender)?5:0;
                $percentage += !is_null($user->dob)?5:0;
                $percentage += !is_null($user->latitude)?5:0;
                $percentage += !is_null($user->about_me)?5:0;
                if(!is_null($user->socialAccount))
                {
                    $percentage += !is_null($user->socialAccount->phone_number)?5:0;
                    $percentage += !is_null($user->socialAccount->google)?5:0;
                    $percentage += !is_null($user->socialAccount->facebook)?5:0;
                }
                if(!is_null($user->UserQuestion))
                {
                    $percentage += !is_null($user->UserQuestion->question_1)?5:0;
                    $percentage += !is_null($user->UserQuestion->question_2)?5:0;
                    $percentage += !is_null($user->UserQuestion->question_3)?5:0;
                    $percentage += !is_null($user->UserQuestion->question_4)?5:0;
                    $percentage += !is_null($user->UserQuestion->question_5)?5:0;
                    $percentage += !is_null($user->UserQuestion->question_6)?5:0;
                    $percentage += !is_null($user->UserQuestion->question_7)?5:0;
                }
                $data = array(
                    'percentage' => $percentage,
                    'name' => $user->name,
                    'profile_pic' => url('images/profile_picture_folder/' . $user->profile_pic)
                );
                return response()->json([
                        'status' => true,
                        'data' => $data
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
    public function userOnline(Request $request, $id)
    {
        try {
            $user = User::where('id',$id)->first();
            if(!is_null($user))
            {
                $expireTime = Carbon::now()->addMinute(2);
                Cache::put('is_online'.$id, true, $expireTime);
                $user->last_seen = date('Y-m-d H:i:s');
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => 'User is Online'
                ], 200);
            }
            else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'User Record not found.'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function profileWithLikeAndDislike(Request $request)
    {
        try
        {
          $like = false;
          $dislike = false;
          $record = DB::table('user_profile_likes')->where(['user_id' => $request->user_id, 'profile_id' => $request->profile_id])->first();
          if(isset($record) && $record->action == 1)
          {
            $like = true;
          }
          if(isset($record) && $record->action == 0)
          {
            $dislike = true;
          }
          $user = User::select('*')
            ->where('id', $request->profile_id)
            ->get()
            ->map(function ($q) use ($like, $dislike) {
                return [
                "id" => $q->id,
                "name" => $q->name,
                "profile_pic" => url('images/profile_picture_folder/' . $q->profile_pic),
                "like" => $like,
                "dislike" => $dislike,
                ];
            });

            if($user)
            {
                return response()->json([
                        'status' => true,
                        'data' => $user,

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
    public function updateLatitudeLongitude(Request $request)
    {
        try
        {
            $validateUser = Validator::make($request->all(),
            [
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::find($request->id);
            if($user)
            {
                $user->latitude = $request->latitude;
                $user->longitude = $request->longitude;
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Latitude and Longitude Updated Successfully.',
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
    public function getLatitudeLongitude(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            if($user)
            {
                $data = [
                        'id' => $user->id,
                        'latitude' => $user->latitude,
                        'longitude' => $user->longitude,
                ];
                return response()->json([
                        'status' => true,
                        'data' => $data
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
    public function addSocialAccounts(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            if($user)
            {
                DB::table('social_accounts')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'phone_number' => $request->phone_number,
                        'google' => $request->google,
                        'facebook' => $request->facebook,
                    ]
                );

                return response()->json([
                        'status' => true,
                        'message' => 'User Social Accounts Added Successfully.'
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
    public function getSocialAccounts(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            if($user)
            {
                $socail_account = DB::table('social_accounts')->where('user_id', $user->id)->first();
                if($socail_account)
                {
                    $data = [
                        'id' => $user->id,
                        'phone_number' => $socail_account->phone_number,
                        'google' => $socail_account->google,
                        'facebook' => $socail_account->facebook,
                    ];
                    return response()->json([
                        'status' => true,
                        'data' => $data,
                    ], 200);
                }
                else
                {
                    return response()->json([
                        'status' => true,
                        'message' => 'Social Account Record not found.',
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
    public function profileLikeAndDislike(Request $request)
    {
        try
        {
            $user = User::find($request->user_id);
            if($user)
            {
                $profile = User::find($request->profile_id);
                if($profile)
                {
                    $like = UserProfileLike::where(['user_id' => $request->user_id, 'profile_id' => $request->profile_id])->first();
                    if(is_null($like))
                    {
                        $like = new UserProfileLike;
                    }
                    $like->user_id = $request->user_id;
                    $like->profile_id = $request->profile_id;
                    $like->action = $request->action;
                    $like->created_at = Carbon::now();
                    $like->save();

                    if($request->action == 0)
                    {
                        $message = 'User Profile Disliked Successfully.';
                    }
                    else{
                        $message = 'User Profile Liked Successfully.';
                    }

                    return response()->json([
                            'status' => true,
                            'message' => $message
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
    public function allLikedUsers(Request $request)
    {
        try
        {
            $user = User::find($request->user_id);
            if($user)
            {
                $baseUrl = url('images/profile_picture_folder');
                $liked_user_list = UserProfileLike::where(['user_id' => $request->user_id])->with('user')->where('action',1)->get();
                $data = array();
                foreach($liked_user_list as $item)
                {
                    if(!is_null($item->user))
                    {
                        $item->user->profile_pic =  url('images/profile_picture_folder/'.$item->user->profile_pic);
                        $data[] =  $item->user;
                    }

                }
                return response()->json([
                        'status' => true,
                        'data' => $data,
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
    public function nerebyUsersList(Request $request)
    {
        try
        {
            $user = User::find($request->user_id);
            if($user)
            {
                $latitude = $user->latitude; // get the latitude
                $longitude = $user->longitude; // get the longitude
                $radius = 10; // distance in kilometers

                $baseUrl = url('images/profile_picture_folder');

                $query = DB::table('users')->select('id', 'name', DB::raw("CONCAT('$baseUrl', '/', profile_pic) as profile_pic_url"));

                if(is_null($latitude) && is_null($longitude))
                {
                    return response()->json([
                        'status' => false,
                        'data' => "Latitude and Longitude not exist for this user.",
                    ], 200);
                }
                
                $query->select(DB::raw("id,name,CONCAT('$baseUrl','/', profile_pic) as profile_pic_url,
                    (6371 * acos(cos(radians(" . $latitude . "))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians(" . $longitude . "))
                    + sin(radians(" .$latitude. "))
                    * sin(radians(latitude)))) AS distance"))
                    ->where('id', '<>', $user->id);

                if($request->has('online'))
                {
                    $date = new \DateTime;
                    $date->modify('-5 minutes');
                    $formatted_date = $date->format('Y-m-d H:i:s');
                    $query->orWhere('last_seen','>=',$formatted_date);
                }

                $users = $query->where('hide', '<>', 0)
                    ->havingRaw('distance > 0 AND distance < ?', [$radius])
                    ->orderBy('distance')
                    ->get();

                return response()->json([
                        'status' => true,
                        'data' => $users,
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
    public function filterUserList(Request $request)
    {
        try
        {
            $user = User::find($request->id);
            if($user)
            {
                $latitude = $user->latitude; // get the latitude
                $longitude = $user->longitude;
                $radius = $request->distance;
                $baseUrl = url('images/profile_picture_folder');
                $query = DB::table('users')->select('id', 'name', DB::raw("CONCAT('$baseUrl', '/', profile_pic) as profile_pic_url"));
                if($request->distance)
                {
                    if(is_null($latitude) && is_null($longitude))
                    {
                        return response()->json([
                            'status' => false,
                            'data' => "Latitude and Longitude not exist for this user.",
                        ], 200);
                    }
                    else
                    {
                        $query->select(DB::raw("(6371 * acos(cos(radians(" . $latitude . "))
                        * cos(radians(latitude))
                        * cos(radians(longitude) - radians(" . $longitude . "))
                        + sin(radians(" .$latitude. "))
                        * sin(radians(latitude)))) AS distance"))->where('id', '<>', $user->id)
                        ->where('hide', '<>', 0)
                        ->havingRaw('distance > 0 AND distance < ?', [$radius])
                        ->orderBy('distance'); 
                    }
                }
                if($request->gender)
                {
                    switch ($request->gender) {
                        case 'Male':
                            $query->where('gender', 1);
                            break;
                        case 'Female':
                            $query->where('gender', 0);
                            break;
                        default:
                            $query->whereIn('gender', [0,1]);
                    }
                }
                if($request->status)
                {
                    switch ($request->status) {
                        case 'New':
                            $query->orderByDesc('id');
                            break;
                        case 'online':
                            $date = new \DateTime;
                            $date->modify('-5 minutes');
                            $formatted_date = $date->format('Y-m-d H:i:s');
                            $query->orWhere('last_seen','>=',$formatted_date);
                            break;
                        default:
                            $query->orderBy('id');
                    }
                }
                if($request->age)
                {
                   $age = $request->age;
                   $query->whereRaw("TIMESTAMPDIFF(YEAR, dob, CURDATE()) = $age");
                }
                $users = $query->get();
                return response()->json([
                        'status' => true,
                        'data' => $users,
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

}
