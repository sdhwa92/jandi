<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventRepository 
{

  /**
   * @var Event
   */
  protected $event;

  /**
   * Event repository constructor
   * 
   * @param Event $event
   */
  public function __construct(Event $event)
  {
    $this->event = $event;
  }

  public function createNewEvent($data)
  {
    $event = new $this->event;

    // die(print_r($data));

    $event->event_type = $data['eventType'];
    $event->created_by = $data['createdBy'];
    $event->name = $data['eventName'];
    $event->address = $data['eventAddress'];
    $event->event_date = $data['eventDate'];
    $event->start_time = $data['startHour'] . ':' . $data['startMin'] . ' ' . $data['startAmPm'];
    $event->end_time = $data['endHour'] . ':' . $data['endMin'] . ' ' . $data['endAmPm'];
    $event->min_head = $data['minHead'];
    $event->max_head = $data['maxHead'];
    $event->memo = $data['eventMemo'];

    $event->save();

    return $event->fresh();
  }
}

?>