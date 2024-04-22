<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Bet;

class EventSelectController extends Controller
{
    public function show($id)
    {
        $event = $this->selectEvent($id);

        if (count($event) > 0)
        {
            return view('event.select_event', ['event' => $event[0] ,'auth_status' => Auth::check()]);
        }
        else
        {
            return view('event.error_event', ['auth_status' => Auth::check()]);
        }
    }

    public function store(Request $request)
    {
        $this->validateSum($request);
        $user = Auth::user();

        if ($this->checkMinusBalance($user, $request))
        {
            $parameters = json_decode($request->parameters, true);
            $this->createBet($user, $parameters, $request);

            return redirect()->route('pari');
        }

        else
        {
            return redirect()->back();
        }
    }

    public function createBet($user, $parameters, $request)
    {
        $eventParameters = Event::select('parameters')->where('id', $parameters['id'])->first();
        $eventParameters = json_decode($eventParameters->parameters, true);

        if ($eventParameters[$parameters['name']][$parameters['team']][$parameters['result']]['status'] === 'waited')
        {
            Bet::create([
                'user_id_better' => $user->id,
                'sum_bet' => $request->sum,
                'coefficient' => $eventParameters[$parameters['name']][$parameters['team']][$parameters['result']]['coefficient'],
                'status' => 'waited',
                'event_id' => $parameters['id'],
                'event_name' => $parameters['name'],
                'event_team' => $parameters['team'],
                'event_result' => $parameters['result'],
            ]);

            $user->update(['balance' => bcsub($user->balance, $request->sum, 2)]);
        }
    }

    public function selectEvent($id)
    {
        return Event::select('id','name_category', 'event_name', 'date_start', 'time_start', 'parameters', 'status')->where('id', $id)->get();
    }

    public function validateSum($request)
    {
        $request->validate([
            'sum' => ['required', 'integer', 'gte:1'],
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
}
