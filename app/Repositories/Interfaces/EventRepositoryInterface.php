<?php

namespace App\Repositories\Interfaces;

use App\Models\Event;
use Illuminate\Support\Collection;

interface EventRepositoryInterface
{
  public function all(): Collection;

  public function createEvent($input);
}

?>