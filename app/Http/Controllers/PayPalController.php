<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use RealRashid\SweetAlert\Facades\Alert;
   
class PayPalController extends Controller
{
    public function handlePayment()
    {
        $product = [];
        $product['items'] = [
            [
                'name' => 'Nike Joyride 2',
                'price' => 112,
                'desc'  => 'Running shoes for Men',
                'qty' => 2
            ]
        ];
  
        $product['invoice_id'] = uniqid();
        $product['invoice_description'] = "Order #{$product['invoice_id']} Bill";
        $product['return_url'] = route('payment.success');
        $product['cancel_url'] = route('payment.cancel');
        $product['total'] = 100;
  
        $paypalModule = new ExpressCheckout;
  
        $res = $paypalModule->setExpressCheckout($product);
        $res = $paypalModule->setExpressCheckout($product, true);
  
        return redirect($res['paypal_link']);
    }
   
    public function paymentCancel()
    {
        Alert::error('Your payment has been declend. The payment cancelation page goes here!', '');
        return redirect()->route('register');
    }
  
    public function paymentSuccess(Request $request)
    {
        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);
  
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            Alert::success('Registration and Payment process got successfull. Please wait for the admin to activate your account', '');
            return redirect()->route('register');
        }
        Alert::error('Error occured!', '');
        return redirect()->route('register');
        // dd('Error occured!');
    }
}