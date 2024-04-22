<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Replenishmenе;
use App\Models\User;
use YooKassa\Client;

class CheckPayments
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check())
        {
            $user = User::find(Auth::user()->id);
            $payments = Replenishmenе::select('id','id_user', 'id_replenishment', 'sum_replenishment', 'service', 'status', 'end_time')->where('id_user', $user->id)->get();
            
            if (count($payments) > 0)
            {
                $client = new Client();
                $client->setAuth($_ENV['APP_YOOKASSA_SHOP_ID'], $_ENV['APP_YOOKASSA_SECRET_KEY']);

                foreach($payments as $value)
                {
                    if ($value['service'] === 'yookassa')
                    {
                        if ($client->getPaymentInfo($value['id_replenishment'])->status === 'succeeded' and $value['status'] === 0)
                        {
                            $user->update([
                                'balance' => bcadd($user->balance, $value['sum_replenishment'], 2)
                            ]);
    
                            $value->update([
                                'status' => 1,
                            ]);
    
                            $value->delete();
                        }
                    }
    
                    if (time() > (int) $value['end_time'])
                    {
                        $value->delete();
                    }
                }
            }
        }

        return $next($request);
    }
}
