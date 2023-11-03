<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentPlan;

class PaymentPlanController extends Controller
{
    public function getPlans()
    {
        try
        {
            $plans = PaymentPlan::get();
            $data = array();
            if(!is_null($plans))
            {
                foreach($plans as $item)
                {
                    $data[] =  array(
                        'contacts_text' => "We offer maximum ".$item->contacts_text." contacts a month chatting text",
                        'video_call' => "Video Call: ".$item->video_call,
                        'upload_photo' => "Upload ".$item->upload_photo." Photos",
                        'short_bio' => $item->short_bio?"Short Bio":'',
                        'basic_profile' => $item->basic_profile?"Basic Verified Profile":'',
                        'month_discount' =>  $item->month_discount." Month Discount"
                    );
                }
            }
            return response()->json([
                'status' => true,
                'data'=> $data
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
