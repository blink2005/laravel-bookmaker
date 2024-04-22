<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use YooKassa\Client;
use App\Models\Replenishmenе;

class AddBalanceController extends Controller
{
    public function create(Request $request)
    {
        return view('add_balance.yookassa', ['auth_status' => Auth::check()]);
    }

    public function store(Request $request)
    {
        $this->validateBalance($request);
        $user = Auth::user();
        if ($this->checkCountPayments($user) < 3)
        {
            $payment = $this->createPayment($request);
            $this->addPaymentDatabase($user, $payment, $request);
            return redirect($payment->confirmation->confirmation_url);
        }
        else
        {
            return view('add_balance.more_error', ['auth_status' => Auth::check()]);
        }
    }

    public function validateBalance($request)
    {
        return $request->validate([
            'sum' => ['required', 'integer', 'gte:1'],
        ]);
    }

    public function createPayment($request)
    {
        $client = new Client();
        $client->setAuth($_ENV['APP_YOOKASSA_SHOP_ID'], $_ENV['APP_YOOKASSA_SECRET_KEY']);

        try {
            $idempotenceKey = uniqid('', true);
            $payment = $client->createPayment(
                array(
                    'amount' => array(
                        'value' => "{$request->sum}.00",
                        'currency' => 'RUB',
                    ),
                    'confirmation' => array(
                        'type' => 'redirect',
                        'locale' => 'ru_RU',
                        'return_url' => route('welcome'),
                    ),
                    'capture' => true,
                ),
                $idempotenceKey
            );
            
        } catch (\Exception $e) {
            $payment = $e;
        }

        return $payment;
    }

    public function addPaymentDatabase($user, $payment, $request)
    {
        Replenishmenе::create([
            'id_user' => $user->id,
            'id_replenishment' => $payment->id,
            'sum_replenishment' => $request->sum,
            'service' => 'yookassa',
            'status' => false,
            'end_time' => bcadd(time(), 600)
        ]);
    }

    public function checkCountPayments($user)
    {
        $count = count(Replenishmenе::select('id_user')->where('id_user', $user->id)->get());
        return $count;
    }
}
