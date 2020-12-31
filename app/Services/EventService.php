<?php

namespace App\Services;

use App\Repositories\Interfaces\EventRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class EventService 
{
  /**
   * @var EventRepository
   */
  protected $eventRepository;

  /**
   * EventService Constructor
   * 
   * @param EventRepositoryInterface $eventRepository
   */
  public function __construct(EventRepositoryInterface $eventRepository)
  {
    $this->eventRepository = $eventRepository;
  }

  /**
   * Get list of events (Only gets all events at the moment)
   */
  public function getEventList()
  {
    $dump = $this->eventRepository->all();
    dd($dump);
    return $this->eventRepository->all();
  }

  /**
   * Create a new event
   * 
   * @param $data
   */
  public function createEvent($data, $userId)
  {

    $data['eventType'] = 1;
    $data['createdBy'] = $userId;

    $validator = Validator::make($data, [
      'eventName' => 'required',
      'eventAddress' => 'required',
      'eventDate' => 'required',
      'startHour' => 'required',
      'startMin' => 'required',
      'startAmPm' => 'required',
      'endHour' => 'required',
      'endMin' => 'required',
      'endAmPm' => 'required',
      'maxHead' => 'required'
    ]);

    // die(print_r($data));

    if ($validator->fails())
    {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    //-- Array for mapping attributes
    $mappedData = array_fill_keys(
      array(
        'event_type', 
        'name', 
        'address', 
        'event_date', 
        'start_time',
        'end_time',
        'min_head',
        'max_head',
        'memo',
        'created_by'
      ), '');

    if ( $data['eventType'] ) 
    {
      $mappedData['event_type'] = $data['eventType'];
    }

    if ( $data['eventName'] )
    {
      $mappedData['name'] = $data['eventName'];
    }

    if ( $data['eventAddress'] )
    {
      $mappedData['address'] = $data['eventAddress'];
    }

    if ( $data['eventDate'] )
    {
      $mappedData['event_date'] = $data['eventDate'];
    }

    if ( $data['startHour'] && $data['startMin'] && $data['startAmPm'] )
    {
      $startTime = $data['startHour'] . ':' . $data['startMin'] . ' ' . $data['startAmPm'];
      $mappedData['start_time'] = $startTime;
    }

    if ( $data['endHour'] && $data['endMin'] && $data['endAmPm'] )
    {
      $endTime = $data['endHour'] . ':' . $data['endMin'] . ' ' . $data['endAmPm'];
      $mappedData['end_time'] = $endTime;
    }

    if ( $data['minHead'] )
    {
      $mappedData['min_head'] = $data['minHead'];
    }

    if ( $data['maxHead'] )
    {
      $mappedData['max_head'] = $data['maxHead'];
    }

    if ( $data['eventMemo'] )
    {
      $mappedData['memo'] = $data['eventMemo'];
    }

    if ( $data['createdBy'] )
    {
      $mappedData['created_by'] = $data['createdBy'];
    }

    $result = $this->eventRepository->createEvent($mappedData);

    return $result;
  }
}

?>