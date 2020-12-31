<?php

namespace App\Repositories\Eloquent;

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
   * @return Collection
   */
  public function all(): Collection
  {
    return $this->model->all();
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