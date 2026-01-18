<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;
use Session;
use Toastr;
use Auth;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth')->except(['locked','unlocked']);
    }
       public function dashboard()
{
    // Total order count and amount
    $total_order_count  = Order::count();
    $total_order_amount = Order::sum('amount');

    // Status-wise counts and amounts
    $pending_count  = Order::where('order_status', 1)->count();
    $pending_amount = Order::where('order_status', 1)->sum('amount');

    $processing_count  = Order::where('order_status', 2)->count();
    $processing_amount = Order::where('order_status', 2)->sum('amount');

    $on_the_way_count  = Order::where('order_status', 3)->count();
    $on_the_way_amount = Order::where('order_status', 3)->sum('amount');

    $on_hold_count  = Order::where('order_status', 4)->count();
    $on_hold_amount = Order::where('order_status', 4)->sum('amount');

    $in_courier_count  = Order::where('order_status', 5)->count();
    $in_courier_amount = Order::where('order_status', 5)->sum('amount');

    $completed_count  = Order::where('order_status', 6)->count();
    $completed_amount = Order::where('order_status', 6)->sum('amount');

    $cancelled_count  = Order::where('order_status', 7)->count();
    $cancelled_amount = Order::where('order_status', 7)->sum('amount');

    $didnt_receive_count  = Order::where('order_status', 8)->count();
    $didnt_receive_amount = Order::where('order_status', 8)->sum('amount');

    // Today’s orders
    $today_order_count  = Order::whereDate('created_at', Carbon::today())->count();
    $today_order_amount = Order::whereDate('created_at', Carbon::today())->sum('amount');
    $today_order_list   = Order::whereDate('created_at', Carbon::today())->get();

    // Total products and customers
    $total_product  = Product::count();
    $total_customer = Customer::count();

    // Latest orders and customers
    $latest_order    = Order::latest()->limit(5)->with('customer', 'product', 'product.image')->get();
    $latest_customer = Customer::latest()->limit(5)->get();

    // Deliveries
    $today_delivery = Order::where('order_status', 5)->whereDate('created_at', Carbon::today())->count();
    $total_delivery = Order::where('order_status', 5)->count();

    // Last week and last month deliveries
    $last_week = Order::where('order_status', 5)
        ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->count();

    $last_month = Order::where('order_status', 5)
        ->whereMonth('created_at', Carbon::now()->subMonth()->month)
        ->count();

    // Monthly sales
    $monthly_sale = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as amount'))
        ->where('order_status', 5)
        ->groupBy('date')
        ->limit(30)
        ->get();

    return view('backEnd.admin.dashboard', compact(
        'total_order_count', 'total_order_amount',
        'pending_count', 'pending_amount', 
        'processing_count', 'processing_amount', 
        'on_the_way_count', 'on_the_way_amount', 
        'on_hold_count', 'on_hold_amount', 
        'in_courier_count', 'in_courier_amount', 
        'completed_count', 'completed_amount', 
        'cancelled_count', 'cancelled_amount', 
        'didnt_receive_count', 'didnt_receive_amount', 
        'today_order_count', 'today_order_amount', 'today_order_list', 
        'total_product', 'total_customer', 
        'latest_order', 'latest_customer', 
        'today_delivery', 'total_delivery', 
        'last_week', 'last_month', 'monthly_sale'
    ));
}

    public function changepassword(){
        return view('backEnd.admin.changepassword');
    }
     public function newpassword(Request $request)
    {
        $this->validate($request, [
            'old_password'=>'required',
            'new_password'=>'required',
            'confirm_password' => 'required_with:new_password|same:new_password|'
        ]);

        $user = User::find(Auth::id());
        $hashPass = $user->password;

        if (Hash::check($request->old_password, $hashPass)) {

            $user->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            Toastr::success('Success', 'Password changed successfully!');
            return redirect()->route('dashboard');
        }else{
            Toastr::error('Failed', 'Old password not match!');
            return back();
        }
    }
    public function locked(){
        // only if user is logged in
        
            Session::put('locked', true);
            return view('backEnd.auth.locked');
        

        return redirect()->route('login');
    }

    public function unlocked(Request $request)
    {
        if(!Auth::check())
            return redirect()->route('login');
        $password = $request->password;
        if(Hash::check($password,Auth::user()->password)){
            Session::forget('locked');
            Toastr::success('Success', 'You are logged in successfully!');
            return redirect()->route('dashboard');
        }
        Toastr::error('Failed', 'Your password not match!');
        return back();
    }
}