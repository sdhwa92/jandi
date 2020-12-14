<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
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

    protected $_eventDetails;

    protected $_eventParticipants;

    protected $_eventTeams;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // $this->middleware('auth');
        $thisEventId = $request->eventId;
        // dd($eventId);

        $this->_eventDetails = $this->_getEventDetails($thisEventId);

        $this->_eventParticipants = $this->_getEventParticipants($thisEventId);

        $this->_eventTeams = $this->_getEventTeams($thisEventId);
    }

    /**
     * Render Even view page
     */
    public function renderViewEventPage($eventId)
    {
        return view('event/viewEvent', [
          'eventDetails' => $this->_eventDetails,
          'eventParticipants' => $this->_eventParticipants,
          'approvedParticipants' => $this->_getEventParticipants($this->_eventDetails->id, $this->_eventDetails->max_head),
          'eventTeams' => $this->_eventTeams,
          'isHost' => $this->isHost($this->_eventDetails),
          'eventDefaultData' => $this->_getDefaultEventData($this->_eventDetails),
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
    public function deleteParticipant(Request $request)
    {
        // dd($request->participantId);
        $participant = Participant::find($request->participantId);
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
     * Loop through inputs and get the participant id to update for each input value 
     * @param Request $request
     * @param int $eventId
     */
    public function selectTeam(Request $request, $eventId)
    {
      $randomList = array();

      foreach($request->input() as $key=>$value){
        
        //-- Any inputs which have a name starting with specific prefix
        if( ( "team-participant-" == substr($key,0,17) ) )
        {
          // dd(strrpos($key,'-'));
          $participantId = substr($key,strrpos($key,'-') + 1);

          //-- 팀이 선택되지 않은 참가자 들의 ID는 ramdomList Array에 추가 시킨다.
          //-- 팀이 선택된 참가자 들의 경우에는, 참가자 ID를 통해 참가자 instance를 불러온 다음 team_id를 지정해준다.
          if ( empty($value) ) 
          {
            array_push($randomList, $participantId);
          }
          else 
          {
            $this->_setTeam($participantId, $value);
          }
        }
      }

      //-- Loop through all participant id contained in the randomList array, and set the team for each id.
      foreach ($randomList as $participantId)
      {
        $this->_setRandomTeam($participantId, $eventId);
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
    private function _getEventParticipants($eventId, $limit = null)
    {
        if ($limit)
        {
          $participants = DB::table('participants')
                          ->leftJoin('teams', 'participants.team_id', '=', 'teams.id')
                          ->select('participants.*', 'teams.team_name')
                          ->where('participants.event_id', $eventId)
                          ->limit($limit)
                          ->get();
        }
        else 
        {
          $participants = DB::table('participants')
                        ->leftJoin('teams', 'participants.team_id', '=', 'teams.id')
                        ->select('participants.*', 'teams.team_name')
                        ->where('participants.event_id', $eventId)
                        ->get();
        }

        return $participants;
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

    /**
     * Function for distributing participants into teams randomly.
     * It adds participant into the team has the lowest count of team members.
     * If there are multiple teams having same number of counts, select randomly between them.
     * 
     * @param $participantId
     * @param $eventId
     */
    private function _setRandomTeam($participantId, $eventId)
    {
      //-- 가장 적은 인원수를 가진 팀부터 한명씩 지정해준다.
      //-- 만약 가장 적은 인원수를 가진 팀이 여러 팀이라면 그중 아무 팀이나 랜덤하게 지정해준다.
      $teams = $this->_eventTeams;
      $teamMemberCounts = array();
      foreach ( $teams as $team ) 
      {
        $teamMembers = Participant::where('event_id', (int)$eventId)->where('team_id', $team->id)->get();
        $countDetails = (object)array(
          'teamId' => $team->id,
          'count' => count($teamMembers)
        );
        array_push($teamMemberCounts, $countDetails);
      }

      $minCount = min(array_column($teamMemberCounts, 'count'));

      $teamsWithMinCount = array_filter($teamMemberCounts, function($teamCount) use ($minCount) {
        return ($teamCount->count == $minCount);
      }); 

      $teamsWithMinCountArrayKeys = array();

      if ( count($teamsWithMinCount) > 0 )
      {
        $teamsWithMinCountArrayKeys = array_keys($teamsWithMinCount);
        // dd($teamsWithMinCountArrayKeys);
        // dd($teamsWithMinCount[$teamsWithMinCountArrayKeys[array_rand($teamsWithMinCountArrayKeys, 1)]]);
        $selectedTeam = $teamsWithMinCount[$teamsWithMinCountArrayKeys[array_rand($teamsWithMinCountArrayKeys, 1)]];
        $this->_setTeam($participantId, $selectedTeam->teamId);
      }
    }

    /**
     * Set team id for the given participant id
     * @param $participantId
     * @param $teamId
     */
    private function _setTeam($participantId, $teamId)
    {
      $participant = Participant::find($participantId);
      $participant->team_id = $teamId;

      if(!$participant->save())
      {
        abort(500, 'Failed to update team for participant id ' . $participant->id);
      }
    }
}
