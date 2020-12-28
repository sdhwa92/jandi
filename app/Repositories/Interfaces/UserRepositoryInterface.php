<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
  public function all(): Collection;

  public function login($input): String;

  public function register($input);

  public function getUserDetails();
}

?>