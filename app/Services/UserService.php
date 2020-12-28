<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\UserRepositoryInterface;
use InvalidArgumentException;

class UserService
{

  /**
   * @var UserRepository
   */
  protected $userRepository;

  /**
   * UserService Constructor
   * 
   * @param UserRepositoryInterface $userRepository
   */
  public function __construct(UserRepositoryInterface $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function login($input)
  {
    return $this->userRepository->login($input);
  }

  public function register($request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'required',
      'c_password' => 'required|same:password',
    ]);

    if ($validator->fails())
    {
      throw new InvalidArgumentException($validator->errors()->first());
    }

    $input = $request->all();

    return $this->userRepository->register($input);
  }

  public function findAllUsers()
  {
    return $this->userRepository->all();
  }

  public function getUserDetails()
  {
    return $this->userRepository->getUserDetails();
  }
}
?>