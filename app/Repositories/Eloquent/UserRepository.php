<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
  /**
   * UserRepository constructor
   * 
   * @param User $model
   */
  public function __construct(User $model)
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

  /**
   * Attempt login
   * 
   * @param $input
   * @return String (access token)
   */
  public function login($input): String
  {
    
    if (Auth::attempt([
      'email' => $input['email'],
      'password' => $input['password']
    ]))
    {
      $user = Auth::user();
      return $user->createToken('MyApp')->accessToken;
    }
    else 
    {
      return '';
    }
  }

  /**
   * Register new user
   * 
   * @param $input
   * @return $result
   */
  public function register($input) 
  {
    $input['password'] = bcrypt($input['password']);
    $user = User::create($input);
    $result['token'] = $user->createToken('MyApp')->accessToken;
    $result['name'] = $user->name;

    return $result;
  }

  /**
   * Get user details
   * 
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function getUserDetails()
  {
    return Auth::user();
  }
}

?>