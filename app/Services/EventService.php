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

  public function createNewEvent($data)
  {

    $data['eventType'] = 1;
    $data['createdBy'] = Auth::id();

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

    $result = $this->eventRepository->createNewEvent($data);

    return $result;
  }
}

?>