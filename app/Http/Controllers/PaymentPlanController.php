<?php

namespace App\Http\Controllers;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;

class PaymentPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentPlan = PaymentPlan::all();
        return view('payment_plans.index', compact('paymentPlan'));
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        
        return view('payment_plans.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       try{
           $request->validate([
               'contacts_text' => 'required', 
               'video_cal' => 'required', 
               'upload_photo' => 'required',
               'short_bio' => 'required', 
               'basic_profile' => 'required', 
               'month_discount' => 'required', 
           ]);
   
           $PaymentPlan = new PaymentPlan();
           $PaymentPlan->contacts_text  = $request->contacts_text;
           $PaymentPlan->video_call  = $request->video_cal;
           $PaymentPlan->upload_photo = $request->upload_photo;
           $PaymentPlan->short_bio = $request->short_bio;
           $PaymentPlan->basic_profile = $request->basic_profile;
           $PaymentPlan->month_discount = $request->month_discount;
           $PaymentPlan->save();
           $message = 'Payment Plan added Successfully.';
           return redirect('payment_plans')->withSuccess($message);
        
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            $message = 'Server Error occurs';
            return redirect()->back()->withError($message);
        }
    }

    /**
     * DisPlay the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paymentPlan =PaymentPlan::where('id',$id)->first();
        
        return view('payment_plans.edit', compact('paymentPlan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all(),$id);
        try{
            $request->validate([
                'contacts_text' => 'required', 
                'video_call' => 'required', 
                'upload_photo' => 'required',
                'short_bio' => 'required', 
                'basic_profile' => 'required', 
                'month_discount' => 'required', 
            ]);
            
            $paymentPlan = PaymentPlan::find($id);
            $paymentPlan->contacts_text = $request->contacts_text;
            $paymentPlan->video_call = $request->video_call;
            $paymentPlan->upload_photo = $request->upload_photo;
            $paymentPlan->short_bio = $request->short_bio;
            $paymentPlan->basic_profile = $request->basic_profile;
            $paymentPlan->month_discount = $request->month_discount;

            $paymentPlan->save();
            $message = 'Payment Plan updated Successfully.';
            return redirect('payment_plans')->withSuccess($message);
        }catch (\Throwable $th) {
            dd($th->getMessage());
            $message = 'Server Error occurs';
            return redirect()->back()->withError($message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
           
            $paymentPlan = PaymentPlan::find($id);
            $paymentPlan->delete();
            $message = 'Payment Plan deleted Successfully.';
            return redirect()->back()->withSuccess($message);
         
         } catch (\Throwable $th) {
             // dd($th->getMessage());
             $message = 'Server Error occurs';
             return redirect()->back()->withError($message);
         }
    }
}
