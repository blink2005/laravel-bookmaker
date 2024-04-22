<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Bet;
use App\Models\User;

class EventEditController extends Controller
{
    public function index()
    {
        $event = $this->selectAllEvents();
        return view('event.all_event', ['auth_status' => Auth::check(), 'event' => $event]);
    }

    public function create($id)
    {
        $event = $this->selectIdevent($id);
        return view('event.edit_event', ['auth_status' => Auth::check(), 'event' => $event]);
    }

    public function save(Request $request)
    {
        $event = $this->selectIdevent($request->event_id);
        $this->validateParameters($request);
        $event->update([
            'parameters' => $request->parameters
        ]);

        return redirect()->route('edit_event_id', ['id' => $request->event_id]);
    }

    public function end(Request $request)
    {
        $event = $this->selectIdevent($request->event_id);
        $event_array_parameters = json_decode($event->parameters, true);

        foreach (json_decode($event->parameters) as $key => $value)
        {
            foreach ($value as $key_2 => $value_2)
            {
                foreach ($value_2 as $key_3 => $value_3)
                {
                    $beters = Bet::select('id','user_id_better', 'sum_bet', 'coefficient')->where(['event_id' => $request->event_id, 'event_name' => $key, 'event_team' => $key_2, 'event_result' => $key_3])->get();
                    
                    if (count($beters) != 0)
                    {
                        foreach ($beters as $key_4)
                        {
                            $bet = Bet::find($key_4->id);

                            if ($event_array_parameters[$key][$key_2][$key_3]['status'] === 'win')
                            {
                                $bet->update(['status' => 'win']);
                                $user_winner = User::find($bet->user_id_better);
                                $win_money = bcmul($bet->sum_bet, $bet->coefficient, 2);
                                $user_winner->update([
                                    'balance' => bcadd($user_winner->balance, $win_money, 2)
                                ]);
                            }

                            if ($event_array_parameters[$key][$key_2][$key_3]['status'] === 'lose')
                            {
                                $bet->update(['status' => 'lose']);
                            }
                        }
                    }
                }
            }
        }

        $event->delete();
        
        return redirect()->route('welcome');
    }

    public function validateParameters($request)
    {
        return $request->validate([
            'parameters' => ['required', 'json'],
        ]);
    }

    public function selectAllEvents()
    {
        return Event::select('event_name', 'id')->get();
    }

    public function selectIdevent($id)
    {
        return Event::find($id);
    }

    public function updateEventParameters($event, $parameters)
    {
        return 2;
    }
}
