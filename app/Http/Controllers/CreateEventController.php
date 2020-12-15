<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CreateEventController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		    $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function renderCreateEventPage()
    {
        return view('event/createEvent');
    }

    /**
     * Create a new event
     * 
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createNewEvent(Request $request)
    {
        $event = new Event;

        // dd($request->eventName);
        $event->event_type = 1;
        $event->created_by = Auth::id();

        $event->name = $request->eventName;
        $event->address = $request->eventAddress;
        $event->event_date = $request->eventDate;
        $event->start_time = $request->startHour . ':' . $request->startMin . ' ' . $request->startAmPm;
        $event->end_time = $request->endHour . ':' . $request->endMin . ' ' . $request->endAmPm;
        $event->min_head = $request->minHead;
        $event->max_head = $request->maxHead;
        $event->memo = $request->eventMemo;

        if(!$event->save()){
            abort(500, 'Error');
        }

		    return redirect()->route("event.view", ['eventId' => $event->id]);
    }
}
