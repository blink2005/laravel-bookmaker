<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Withdrawal;

class WithdrawalCreateController extends Controller
{
    public function create()
    {
        return view('withdrawal.withdrawal_page', ['auth_status' => Auth::check()]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $this->validateWithdrawal($request);
        if ($this->checkMinusBalance($user, $request))
        {
            $this->updateBalance($user, $request);
            $this->addInTableWithdrawal($user, $request);

            return view('withdrawal.withdrawal_true', ['auth_status' => Auth::check()]);
        }
        else
        {
            return view('withdrawal.withdrawal_false', ['auth_status' => Auth::check()]);
        }
    }

    public function validateWithdrawal($request)
    {
        return $request->validate([
            'sum' => ['required', 'integer', 'gte:1'],
            'description' => ['required', 'max:250'],
        ]);
    }

    public function checkMinusBalance($user, $request)
    {
        if (bcsub($user->balance, $request->sum, 2) >= 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function updateBalance($user, $request)
    {
        $user->update(['balance' => bcsub($user->balance, $request->sum, 2)]);
    }

    public function addInTableWithdrawal($user, $request)
    {
        Withdrawal::create([
            'id_user' => $user->id,
            'sum_withdrawals' => $request->sum,
            'description' => $request->description,
            'status' => 'waited'
        ]);
    }
}
