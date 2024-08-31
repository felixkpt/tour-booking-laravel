<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Validations\User\UserValidationInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private UserValidationInterface $userValidationInterface,
    ) {}

    /**
     * Register a new user.
     * Validates the input and creates a new user with a role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        return $this->userRepositoryInterface->register($request);
    }

    /**
     * Login The User
     *
     * This method handles user login requests. It validates the incoming request data,
     * attempts to authenticate the user, logs the authentication attempt (whether successful or not),
     * and returns an appropriate response.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        return $this->userRepositoryInterface->login($request);
    }

    /**
     * Send a password reset link to the user's email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordResetLink(Request $request)
    {
        return $this->userRepositoryInterface->passwordResetLink($request);
    }

    /**
     * Verify the password reset token and retrieve the associated email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmail(Request $request)
    {
        return $this->userRepositoryInterface->getEmail($request);
    }

    /**
     * Set a new password for the user.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordSet(Request $request)
    {
        return $this->userRepositoryInterface->passwordSet($request);
    }

    /**
     * Update the user's profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(Request $request)
    {
        return $this->userRepositoryInterface->profileUpdate($request);
    }

    /**
     * Update the user's password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword()
    {
        return $this->userRepositoryInterface->updateSelfPassword();
    }

    /**
     * Retrieve the user's login logs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginLogs()
    {
        return $this->userRepositoryInterface->loginLogs();
    }

    /**
     * Logout the user and revoke the current access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        return $this->userRepositoryInterface->logout($request);
    }
}
