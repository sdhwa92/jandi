<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class UserController extends Controller
{
  public $successStatus = 200;

  /**
   * @var User Service
   */
  private $userService;

  /**
   * Constructor of User Controller
   * 
   * @param UserService $userService
   */
  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  /**
   * login api
   * 
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request)
  {
    $data = $request->only([
      'email',
      'password',
    ]);

    $accessToken = $this->userService->login($data);

    if (!empty($accessToken))
    {
      $success['token'] = $accessToken;
      return response()->json(['success' => $success], $this->successStatus);
    }
    else 
    {
      return response()->json(['error' => 'Unauthorised'], 401);
    }
  }

  /**
   * Register api
   * 
   * @param Request $request
   * @return \Illuminate\Http\Response
   */
  public function register(Request $request)
  {
    $result = ['status' => $this->successStatus];

    try 
    {
      $result['data'] = $this->userService->register($request);
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
   * User details api
   * 
   * @return \Illuminate\Http\Response
   */
  public function details()
  {
    $result = ['status' => $this->successStatus];

    $user = $this->userService->getUserDetails();

    if ($user)
    {
      $result['data'] = $user;
    }
    else 
    {
      $result = [
        'status' => 500,
        'error' => 'User Not Found'
      ];
    }

    return response()->json($result, $result['status']);
  }
}
