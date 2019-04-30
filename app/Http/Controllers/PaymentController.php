<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Redirect;
class PaymentController extends Controller
{
    public function view()
    {
        if (Auth::guest())
            return redirect()->route('login');
        $userid= Auth::user()->id;
        $subscription=DB::table('users')->where('id', $userid)->get();
        return view('payment',compact('subscription'));
    }
    public function pay(Request $request)
    {

$userid= Auth::user()->id;
$test=$request->defaultCheck1;
DB::table('users')
            ->where('id', $userid)
            ->update(['isPaid' => 0]);

    $selection=$request->selection;
    if($selection==1)
    {

        $value=1;
        $creditcard=$request->cardNumber;
        $name=$request->username;
        $cvv=$request->cvv;
        $userid= Auth::user()->id;
        DB::table('users')
            ->where('id', $userid)
            ->update(['isPaid' => $value]);
        return Redirect::to('/profile')->with('payment_message_success','You have been successfully subscribed! Welcome, thank you, and we hope you enjoy your stay!');

    }
        return Redirect::to('/profile')->with('payment_message_cancel','We are sad to see you go... Please come again soon! Your subscription status has been canceled.');
    }
}
