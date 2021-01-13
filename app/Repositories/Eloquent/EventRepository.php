<?php

namespace App\Repositories\Eloquent;

use App\Http\Resources\Event as EventResource;
use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Support\Collection;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{
  /**
   * EventRepository constructor
   * 
   * @param Event $model
   */
  public function __construct(Event $model)
  {
    parent::__construct($model);
  }

  /**
   * return all events
   */
  public function all(): Array
  {
    $result = EventResource::collection($this->model->all());
    return $result->resolve();
  }

  public function createEvent($input)
  {
    // dd($input);
    $event = $this->create($input);
    $result['eventId'] = $event->id;

    return $result;
  }
}

?>