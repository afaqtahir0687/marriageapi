<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Session;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(session()->get('user') == null)
        // {
        //     return redirect('login');
        // }
        $users = User::with('socialAccount' , 'UserQuestion')->get();
        return view('admin.users.users',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(session()->get('user') == null)
        {
            return redirect('login');
        }
        $user = User::with('likes' ,'blockedUsers')->findOrFail($id);
        return view('admin.users.userdata', compact('user'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $user = User::findOrFail($id);
        $user->hide = $request->input('hidden') == 0 ? 0 : 1;
        $user->save();
        $message = $user->hide ? 'User hidden successfully.' : 'User unhidden successfully.';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ban(Request $request, $id, $status)
    {
        $user = User::findOrFail($id);
        $user->status = $status;
        $user->save();
        $message = $user->status == 1 ? 'User unbloced successfully.' : 'User blocked successfully.';
        return redirect()->back()->withSuccess($message);
    }

    public function hide(Request $request, $id, $status)
    {
        $user = User::findOrFail($id);
        $user->hide = $status;
        $user->save();
        $message = $user->hide ? 'User hidden successfully.' : 'User unhidden successfully.';
        return redirect()->back()->withSuccess($message);
    }
}
