<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use stdClass;

class EventController extends Controller
{

    const HOURS = [
      '01',
      '02',
      '03',
      '04',
      '05',
      '06',
      '07',
      '08',
      '09',
      '10',
      '11',
      '12',
    ];

    const MINS = [
      '00',
      '15',
      '30',
      '45'
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		    // $this->middleware('auth');
    }

    /**
     * Render Even view page
     */
    public function renderViewEventPage($eventId)
    {
        $eventDetails = $this->_getEventDetails($eventId);

        $eventParticipants = $this->_getEventParticipants($eventId);

        $eventTeams = $this->_getEventTeams($eventId);
        // dd($eventDetails);
        return view('event/viewEvent', [
          'eventDetails' => $eventDetails,
          'eventParticipants' => $eventParticipants,
          'eventTeams' => $eventTeams,
          'isHost' => $this->isHost($eventDetails),
          'eventDefaultData' => $this->_getDefaultEventData($eventDetails),
          'hoursOptions' => self::HOURS,
          'minsOptions' => self::MINS
          ]
        );
    }

    /**
     * Update event details
     */
    public function updateEvent(Request $request, $id)
    {
      $event = Event::find($id);

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

      return redirect()->route("event.view", [$id]);
    }

    /**
     * Register participant to the event
     * @param $request
     */
    public function registerParticipant(Request $request)
    {
        $currentEventId = $request->eventId;
        // dd($currentEventId);
        $participant = new Participant();

        $participant->event_id = $currentEventId;
        $participant->name = $request->participantName;

        if(!$participant->save()){
          abort(500, 'Error');
        }
        
        return redirect()->route('event.view', [$currentEventId]);
    }

    /**
     * Delete participant
     * @param $participantId
     */
    public function deleteParticipant($participantId)
    {
        // dd($participantId);
        $participant = Participant::find($participantId);
        if (!$participant->delete()) {
          abort(500, 'Error');
        }
        
        return redirect()->back();
    }

    
    /**
     * Craete new team in the event
     * @param Request $request
     * @param int $eventId
     */
    public function createTeam(Request $request, $eventId)
    {
      $team = new Team();
      $team->team_name = $request->teamName;
      $team->event_id = $eventId;

      if(!$team->save()){
        abort(500, 'Error');
      }

      return redirect()->route('event.view', [$eventId]);
    }

    /**
     * Update participant with a selected team
     * @param Request $request
     * @param int $eventId
     */
    public function selectTeam(Request $request, $eventId)
    {
      foreach($request->input() as $key=>$value){
        if( !empty($value) && ( "team-participant-" == substr($key,0,17) ) ){
          // dd(strrpos($key,'-'));
          $participantId = substr($key,strrpos($key,'-') + 1);
          $participant = Participant::find($participantId);
          $participant->team_id = $value;

          if(!$participant->save())
          {
            abort(500, 'Failed to update team for participant id ' . $participant->id);
          }
        }
      }

      return redirect()->route('event.view', [$eventId]);
    }

    /**
     * Check if the user is host
     * @param $eventDetails
     */
    public function isHost($eventDetails)
    {
      if (!Auth::check())
      {
        // print('Guest');
        return false;
      }
     
      if ( $eventDetails->created_by != Auth::user()->id ) {
        return false;
      }

      return true;
    }

    /**
     * Get event detail
     * @param $id: event id
     */
    private function _getEventDetails($id) 
    {
        return Event::Where('id', $id)->first();
    }

    /**
     * Get list of participants of the event
     */
    private function _getEventParticipants($eventId)
    {
        return Participant::Where('event_id', $eventId)->get();
    }

    /**
     * Get list of teams from the event
     */
    private function _getEventTeams($eventId)
    {
      return Team::Where('event_id', $eventId)->get();
    }

    /**
     * Get the default event data
     * @param Event $data
     * @return {
     *    name
     *    address
     *    date
     *    startHr
     *    startMin
     *    endHr
     *    endMin
     *    min
     *    max
     *    memo
     * }
     */
    private function _getDefaultEventData(Event $data)
    {
      $results = new stdClass();

      $results->name = $data->name;
      $results->address = $data->address;
      $results->date = $data->event_date;

      if ( !empty($data->start_time) ) 
      {
        $timeSplit = explode(":", $data->start_time);
        $results->startHr = $timeSplit[0];
        $results->startMin = substr($timeSplit[1], 0, 2);
        $results->startAmPm = substr($timeSplit[1], -2, 2);
      }
      else
      {
        $results->startHr = "01";
        $results->startMin = "00";
        $results->startAmPm = "am";
      }

      if ( !empty($data->end_time) ) 
      {
        $timeSplit = explode(":", $data->end_time);
        $results->endHr = $timeSplit[0];
        $results->endMin = substr($timeSplit[1], 0, 2);
        $results->endAmPm = substr($timeSplit[1], -2, 2);
      }
      else
      {
        $results->endHr = "01";
        $results->endMin = "00";
        $results->endAmPm = "am";
      }

      $results->min = $data->min_head;
      $results->max = $data->max_head;
      $results->memo = $data->memo;

      return $results;
    }
}
