<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $transactionNumber = 'ORDER-' . time() . '-' . $user->id;

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'plan_id' => $request->plan_id,
            'transaction_number' => $transactionNumber,
            'total_amount' => $request->amount, // This should be calculated based on the plan
            'payment_status' => 'pending',
        ]);

        $payload = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_number,
                'gross_amount' => (int) $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => '000000000000'
            ],
            'item_details' => [
                'id' => $transaction->plan_id,
                'price' => (int) $transaction->total_amount,
                'quantity' => 1,
                'name' => $transaction->plan->title,
            ]
        ];

        try{
            $snapToken = Snap::getSnapToken($payload);
            $transaction->update(['snap_token' => $snapToken]);
            $transaction->save();

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
