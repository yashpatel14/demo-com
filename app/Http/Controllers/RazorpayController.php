<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    public function payment(Request $request)
    {
        $request->validate([

            'amount' => 'required|numeric|min:1',
        ]);

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $order = $api->order->create([
            'receipt' => 'order_' . uniqid(),
            'amount' => $request->amount * 100, // Convert to paise
            'currency' => 'INR',
            'payment_capture' => 1
        ]);

        $payment = Payment::create([

            'product_id' => $request->product_id,
            'amount' => $request->amount,
            'order_id' => $order['id'],
            'status' => 0
        ]);

        return view('razorpay.payment', [
            'orderId' => $order['id'],
            'product_id' => $request->product_id,
            'amount' => $request->amount,
            'razorpayKey' => env('RAZORPAY_KEY'),
        ]);
    }

    public function success(Request $request)
    {
        if (!$request->razorpay_payment_id || !$request->razorpay_order_id) {
            return redirect('/');
        }

        $payment = Payment::where('order_id', $request->razorpay_order_id)->first();

        if (!$payment) {
            return back()->with('error', 'Invalid payment record.');
        }

        $payment->update([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 1 // Mark as successful
        ]);

        return redirect('/razorpay')->with([
            'success' => 'Payment Successful!',
            'payment' => $payment
        ]);
    }

}
