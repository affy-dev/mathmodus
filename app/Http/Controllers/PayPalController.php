<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use RealRashid\SweetAlert\Facades\Alert;
   
class PayPalController extends Controller
{
    public function handlePayment()
    {
        $order = '0011';

        //Code to Email Book To User

        return $order;
    }
   
    public function paymentCancel()
    {
        Alert::error('Your payment has been declend. The payment cancelation page goes here!', '');
        // return redirect()->route('register');
    }
  
    public function paymentSuccess(Request $request)
    {
        Alert::success('Your payment has been successfull. The payment cancelation page goes here!', '');
        // return redirect()->route('register');
    }
}