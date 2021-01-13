<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Services\EventService;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

  public $successStatus = 200;

  /**
   * @var Event Service
   */
  private $eventService;

  /**
   * Constructor of Evnet Controller
   * 
   * @param EventService $eventService
   */
  public function __construct(EventService $eventService)
  {
    $this->eventService = $eventService;
  }

  /**
   * Return all events details
   * 
   * @param Request $request
   * @return \Illuminate\Http\Response
   */
  public function getEventList()
  {
    $result = ['status' => $this->successStatus];

    try
    {
      $result['data'] = $this->eventService->getEventList();
    }
    catch(Exception $e)
    {
      $result = [
        'status' => 500,
        'error' => $e->getMessage()
      ];
    }

    return response()->json($result, $result['status']);
  }

  /**
   * Create a new event
   * 
   * @param $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function createEvent(Request $request)
  {
    // die(print_r($request->input()));
    $data = $request->only([
      'eventName',
      'eventAddress',
      'eventDate',
      'startHour',
      'startMin',
      'startAmPm',
      'endHour',
      'endMin',
      'endAmPm',
      'minHead',
      'maxHead',
      'eventMemo'
    ]);

    $result = ['status' => 200];

    try 
    {
      $result['data'] = $this->eventService->createEvent($data , $request->user()->id);
    }
    catch (Exception $e) 
    {
      $result = [
        'status' => 500,
        'error' => $e->getMessage()
      ];
    }

    return response()->json($result, $result['status']);
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

    // return redirect()->route("event.view", [$id]);
  }

  /**
   * Return the event details
   */
  public function getEventDetails(Request $request) 
  {
    return Event::Where('id', $request->eventId)->first();
  }
}
