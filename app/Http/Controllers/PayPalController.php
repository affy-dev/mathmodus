<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use RealRashid\SweetAlert\Facades\Alert;
use App\User;
use App\Orders;

class PayPalController extends Controller
{
    public function handlePayment(Request $request)
    {
        if(!session()->has('startPayment')) {
            return redirect()->route('frontend.home');
        }
        session()->forget('startPayment');
        $userId = session('userId');
        return view('frontend.payment-page', compact('userId'));
    }

    public function paymentSuccess(Request $request)
    {
        try {

            $orderData = $request->all();
            User::where('id', $orderData['userId'])->update(['user_status' => 1, 'payment_status' => 1]);
            $order = Orders::create([
                'user_id'           => $orderData['userId'],
                'paypal_order_id'   => $orderData['orderID'],
                'status'            => 'success',
                'payer_id'          => $orderData['payerID']
            ]);
            session()->forget('userId');
            return $order;
          } catch (\Exception $e) {
              return $e->getMessage();
          }
    }
}