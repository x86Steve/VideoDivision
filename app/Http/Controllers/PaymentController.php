<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
class PaymentController extends Controller
{
    public function view()
    {
        return view('payment');
    }
    public function pay(Request $request)
    {
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
            ->update(['isPaid' => $request->$value]);
    }



{








        return redirect()->route('home');
    }
}
