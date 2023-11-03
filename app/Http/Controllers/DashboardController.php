<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\User;
use Cache;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        if(session()->get('user') == null)
        {
            return redirect('login');
        }
        $users = User::where('created_at','>=',Carbon::now()->subdays(7))->count();
        $m_users = User::where('created_at','>=',Carbon::now()->subdays(30))->count();
        $date = new \DateTime;
        $date->modify('-5 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $online = User::where('last_seen','>=',$formatted_date)->count();
        return view('admin.dashboard',['users' => $users, 'online' => $online, 'm_users' => $m_users]);
    }
}
