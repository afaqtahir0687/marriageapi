<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserQuestion;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendEmailVerificationCode;
use Cache;
use Carbon\Carbon;
class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $code = random_int(100000, 999999);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'email_verification_code' => $code,
                'password' => Hash::make($request->password)
            ]);

            Mail::to($request->email)->send(new sendEmailVerificationCode($code));
            $user = User::where('email', $request->email)->first();
            $expireTime = Carbon::now()->addMinute(2);
            Cache::put('is_online'.$user->id, true, $expireTime);
            $user->last_seen = date('Y-m-d H:i:s');
            $user->save();
            $userData = User::select('*')
            ->where('id', $user->id)
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
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data' => $userData,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $expireTime = Carbon::now()->addMinute(2);
            Cache::put('is_online'.$user->id, true, $expireTime);
            $user->last_seen = date('Y-m-d H:i:s');
            $user->save();
            $userData = User::select('*')
            ->where('id', $user->id)
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

            event(new Registered($user));
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'data' => $userData,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function userQuestions(Request $request)
    {
        try {
            $question  = UserQuestion::where('user_id',$request->user_id)->first();
            if(is_null($question ))
            {
                $question = new UserQuestion;
            }
            $question->question_1 = $request->question_1;
            $question->question_2 = $request->question_2;
            $question->question_3 = $request->question_3;
            $question->question_4 = $request->question_4;
            $question->question_5 = $request->question_5;
            $question->question_6 = $request->question_6;
            $question->question_7 = $request->question_7;
            $question->user_id = $request->user_id;
            $question->save();
            return response()->json([
                'status' => true,
                'message' => 'Answers Added Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function userDelete(Request $request)
    {
        try {

            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }


            DB::table('users')->where('email', $request->email)->delete();
            return response()->json([
                'status' => true,
                'message' => 'User Deleted Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'gender' => 'required',
                'dob' => 'required',
                'location' => 'required',
                'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $insArr = array();
            $insArr['name'] = $request->name;
            $insArr['gender'] = $request->gender;
            $insArr['dob'] = $request->dob;
            $insArr['location'] = $request->location;
            $insArr['about_me'] = $request->about_me;

            if ($request->hasFile('profile_pic')) {
                $profile_pic = $request->file('profile_pic');
                $filename = time() . '.' . $profile_pic->getClientOriginalExtension();
                $profile_pic->move(public_path('images/profile_picture_folder'), $filename);
                $insArr['profile_pic'] = $filename;
            }
            $user = User::where('id',$request->id)->first();
            if(!is_null($user))
            {
                User::where('id',$request->id)->update($insArr);
                return response()->json([
                    'status' => true,
                    'message' => 'User Record Updated Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
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

    public function updateProfileImage(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $id = $request->id;
            $user = User::find($id);
            if($user)
            {
                if ($request->hasFile('profile_pic')) {
                        $profile_pic = $request->file('profile_pic');
                        $filename = time() . '.' . $profile_pic->getClientOriginalExtension();
                        $profile_pic->move(public_path('images/profile_picture_folder'), $filename);
                }
                $user->profile_pic = $filename;
                $user->save();
                $user->profile_pic = url('images/profile_picture_folder/' . $filename);
                return response()->json([
                        'status' => true,
                        'message' => 'Profile Picture Updated Successfully.',
                        'user_data' => $user,
                ], 200);
            }
            else
            {
                return response()->json([
                        'status' => true,
                        'message' => 'User Record not found.',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function showProfileImage(Request $request)
    {
        try {

            $id = $request->id;
            $user = User::find($id);

            if($user)
            {
                $user->profile_pic = url('images/profile_picture_folder/' . $user->profile_pic);
                return response()->json([
                        'status' => true,
                        'profile_pic' => $user->profile_pic,
                ], 200);
            }
            else
            {
                return response()->json([
                        'status' => true,
                        'message' => 'User Record not found.',
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function hideProfile(Request $request)
    {
        try {

            $id = $request->id;
            $user = User::find($id);

            if($user)
            {
                $user->hide = $request->hide;
                $user->save();

                return response()->json([
                        'status' => true,
                        'message' => 'User Hide Successfully.',
                ], 200);
            }
            else
            {
                return response()->json([
                        'status' => true,
                        'message' => 'User Record not found.',
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginUserWithSocial(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'type' => 'required',
                'id' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if( $request->type == 'facebook')
            {
                $insArr['facebook_id'] = $request->id;
                $user = User::where('facebook_id',$request->id)->first();
                if(is_null($user))
                {
                    $user = new User;
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = Hash::make('dummypass');
                    $user->facebook_id = $request->id;
                    $user->save();                }
            }
            else
            {
                $insArr['google_id'] = $request->id;
                $user = User::where('google_id',$request->id)->first();
                if(is_null($user))
                {
                    $user = new User;
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = Hash::make('dummypass');
                    $user->google_id = $request->id;
                    $user->save();
                }
            }
            $expireTime = Carbon::now()->addMinute(2);
            Cache::put('is_online'.$user->id, true, $expireTime);
            $user->last_seen = date('Y-m-d H:i:s');
            $user->save();
            $userData = User::select('*')
            ->where('id', $user->id)
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
            return response()->json([
                'status' => true,
                'message' => 'User Login Successfully',
                'data' => $userData,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
