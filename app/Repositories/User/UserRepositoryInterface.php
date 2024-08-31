<?php

namespace App\Repositories\User;

use App\Repositories\CommonRepoActionsInterface;
use Illuminate\Http\Request;

interface UserRepositoryInterface extends CommonRepoActionsInterface
{
    /**
     * Display a listing of the users.
     *
     * @return mixed
     */
    public function index();

    /**
     * Register a new user.
     * Validates the input and creates a new user with a role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request);

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
    public function login(Request $request);

    /**
     * Send a password reset link to the user's email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordResetLink(Request $request);

    /**
     * Verify the password reset token and retrieve the associated email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmail(Request $request);

    /**
     * Set a new password for the user.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordSet(Request $request);

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @param array $data
     * @return mixed
     */
    public function store(Request $request, $data);

    /**
     * Update the authenticated user's profile.
     *
     * @param Request $request
     * @return mixed
     */
    public function profileUpdate(Request $request);

    /**
     * Update the authenticated user's password.
     *
     * @return mixed
     */
    public function updateSelfPassword();

    /**
     * Resend verification token to the specified user.
     *
     * @param int $userId
     * @return mixed
     */
    public function resendToken($userId);

    /**
     * Automatically log in the specified user.
     *
     * @param int $userId
     * @return mixed
     */
    public function autoLoginUser($userId);

    /**
     * Retrieve login logs for the authenticated user.
     *
     * @return mixed
     */
    public function loginLogs();

    /**
     * List all attempted logins.
     *
     * @return mixed
     */
    public function listAttemptedLogins();

    /**
     * Logout the user and revoke the current access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request);
}
