<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
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
    }

        return redirect()->route('home');

    }
}
