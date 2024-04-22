<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Withdrawal;
use App\Models\User;

class WithdrawalCheckController extends Controller
{
    public function create()
    {
        $withdrawal = $this->getFirstWithdrawal();
        
        if ($withdrawal != null)
        {
            return view('withdrawal.withdrawal_check', ['auth_status' => Auth::check(), 'withdrawal' => $withdrawal]);
        }

        else
        {
            return view('withdrawal.withdrawal_check_null', ['auth_status' => Auth::check()]);
        }
    }

    public function cancel(Request $request)
    {
        $withdrawal = $this->getIdWithdrawal($request->withdrawal_id);

        if ($withdrawal != null and $withdrawal->status === 'waited')
        {
            //return $withdrawal->id_user;
            $user = User::find($withdrawal->id_user);

            $user->update([
                'balance' => bcadd($user->balance, $withdrawal->sum_withdrawals, 2)
            ]);
            
            $withdrawal->update([
                'status' => 'cancel',
            ]);
        }

        return redirect()->route('payments');
    }

    public function accept(Request $request)
    {
        $withdrawal = $this->getIdWithdrawal($request->withdrawal_id);

        if ($withdrawal != null and $withdrawal->status === 'waited')
        {
            $withdrawal->update([
                'status' => 'accept',
            ]);
        }

        return redirect()->route('payments');
    }

    public function getIdWithdrawal($id)
    {
        return Withdrawal::find($id);
    }

    public function getFirstWithdrawal()
    {
        return Withdrawal::select('id','id_user', 'sum_withdrawals', 'description')->where('status', 'waited')->first();
    }
}
