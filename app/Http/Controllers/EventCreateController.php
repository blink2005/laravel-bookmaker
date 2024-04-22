<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventCreateController extends Controller
{
    public function create()
    {
        return view('event.create_event', ['auth_status' => Auth::check()]);
    }

    public function store(Request $request)
    {
        $this->validateEventCreate($request);
        $event = $this->eventCreate($request);
        return redirect()->route('event', ['id' => $event->id]);
    }

    public function validateEventCreate($request)
    {
        return $request->validate([
            'category' => ['required'],
            'name' => ['required'],
            'date' => ['required'],
            'time' => ['required'],
            'parameters' => ['required', 'json'],
        ]);
    }

    public function eventCreate($request)
    {
        return Event::create([
            'name_category' => $request->category,
            'event_name' => $request->name,
            'date_start' => $request->date,
            'time_start' => $request->time,
            'parameters' => $request->parameters,
            'status' => false
        ]);
    }
}
