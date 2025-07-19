<?php

namespace App\Repositories\User;

use App\Mail\SendPassword;
use App\Models\AuthenticationLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Repositories\CommonRepoActions;
use App\Repositories\SearchRepo\SearchRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    use CommonRepoActions;

    function __construct(protected User $model) {}

    public function index($id = null)
    {

        $users = $this->model::with(['roles', 'permissions'])
            ->when(request()->status == 1, fn($q) => $q->where('status_id', activeStatusId()))
            ->when($id, fn($q) => $q->where('id', $id))
            ->when(request()->role_id, function ($q) {
                if (request()->has('negate')) {
                    $q->whereDoesntHave('roles', function ($q) {
                        $q->where('roles.id', request()->role_id);
                    });
                } else {
                    $q->whereHas('roles', function ($q) {
                        $q->where('roles.id', request()->role_id);
                    });
                }
            });

        if ($this->applyFiltersOnly) return $users;

        $uri = '/admin/settings/users/';

        $users = SearchRepo::of($users, ['name', 'id'])
            ->setModelUri($uri)
            ->addColumn('Created_by', 'getUser')
            ->addColumn('Roles', function ($user) {
                return implode(', ', $user->roles()->get()->pluck('name')->toArray());
            })
            ->addFillable('password_confirmation', [], 'avatar')
            ->addFillable('roles_list', ['input' => 'dropdown', 'type' => 'multiple', 'dropdownSource'=> '/api/admin/settings/role-permissions/roles'], 'two_factor_enabled')
            ->addFillable('permissions_list', ['input' => 'dropdown', 'type' => 'multiple', 'dropdownSource'=> '/api/admin/settings/role-permissions/permissions'], 'roles_multiplelist')
            ->addFillable('two_factor_enabled', ['input' => 'input', 'type' => 'checkbox'], 'theme')
            ->addFillable('allowed_session_no', ['input' => 'input', 'type' => 'number', 'min' => 1, 'max' => 10], 'theme');

        return response(['results' => $id ? $users->first() : $users->paginate(), 'status' => true]);
    }

    /**
     * Register a new user.
     * Validates the input and creates a new user with a role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate input data
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6',
            ]
        );

        // Handle validation failure
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        try {
            DB::beginTransaction();
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // Assign default role to the user
            $role = Role::where('name', 'user')->first();
            $user->assignRole($role);
            $user->default_role_id = $role->id;
            $user->save();

            // Generate and assign an API token
            $user->token = $user->createToken("API TOKEN")->plainTextToken;

            // Commit the transaction
            DB::commit();

            return response([
                'status' => true,
                'message' => 'User Created Successfully',
                'results' => $user
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
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
        try {
            // Validate the request data
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',  // Email is required and must be a valid email address
                    'password' => 'required'      // Password is required
                ]
            );

            // If validation fails, log the attempt as unsuccessful and return a validation error response
            if ($validateUser->fails()) {
                $this->logAuthenticationAttempt($request, false);
                return response()->json([
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401); // Return a 401 Unauthorized response with validation errors
            }


            // Attempt to authenticate the user with the provided credentials
            if (!Auth::attempt($request->only(['email', 'password']))) {

                // Retrieve the user by email if it exists
                $userByMail = User::where('email', $request->email)->first();
                if ($userByMail) {
                    $this->logAuthenticationAttempt($request, false, $userByMail); // Log the unsuccessful attempt
                }

                return response()->json([
                    'message' => 'Email & Password do not match our records.',
                    'errors' => [
                        'email' => ['Email & Password do not match our records.']
                    ]
                ], 401); // Return a 401 Unauthorized response with an error message
            }

            // Authentication successful, retrieve the authenticated user
            $user = User::find(auth()->id());

            $this->logAuthenticationAttempt($request, true, $user); // Log the successful attempt

            // Generate an API token for the authenticated user
            $user->token = $user->createToken("API TOKEN")->plainTextToken;

            // Return a successful response with the user data and token
            return response()->json([
                'message' => 'User Logged In Successfully',
                'results' => $user,
            ], 200); // Return a 200 OK response with the user details
        } catch (\Throwable $th) {
            // Catch any exceptions and return a 500 Internal Server Error response
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Send a password reset link to the user's email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordResetLink(Request $request)
    {
        // Validate the user's email
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users',
            ]
        );

        // Handle validation failure
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        // Generate a password reset token
        $token = Str::random(64);

        // Store the token in the password_resets table
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Send password reset email
        Mail::send('emails.auth.forgotPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return response(['message' => 'We have emailed your password reset link!']);
    }

    /**
     * Verify the password reset token and retrieve the associated email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmail(Request $request)
    {
        // Find the password reset entry by token
        $password_reset = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        // Handle invalid token
        if (!$password_reset)
            return response(['message' => 'Invalid token!'], 422);

        return response(['results' => $password_reset], 200);
    }

    /**
     * Set a new password for the user.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordSet(Request $request)
    {
        // Validate the input data
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed|min:6',
        ]);

        // Verify the reset token
        $password_reset = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        // Handle invalid token
        if (!$password_reset)
            return response(['message' => 'Invalid token!'], 422);

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Generate a new API token
        $user->token = $user->createToken("API TOKEN")->plainTextToken;

        // Delete the password reset token
        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return response(['message' => 'Your password has benn updated successfully!', 'results' => $user], 200);
    }


    /**
     * Helper function to log authentication attempts
     *
     * @param Request $request
     * @param bool $success Indicates whether the authentication attempt was successful
     * @param User|null $user The authenticated user, if the attempt was successful
     * @return void
     */
    protected function logAuthenticationAttempt(Request $request, bool $success, User $user = null)
    {
        AuthenticationLog::create([
            'authenticatable_type' => User::class, // The type of the authenticatable model (User)
            'authenticatable_id' => $user ? $user->id : null, // The ID of the authenticatable model, if available
            'ip_address' => $request->ip(), // The IP address from which the request was made
            'user_agent' => $request->header('User-Agent'), // The user agent string from the request
            'login_at' => now(), // The timestamp of the login attempt
            'login_successful' => $success, // Indicates whether the login attempt was successful
        ]);
    }

    public function store(Request $request, $data)
    {

        if (!$request->id) {
            $data['password'] = bcrypt($data['password']);
        }

        $user = $this->autoSave($data);

        if (!$user->default_role_id) {
            $user->default_role_id = $request->roles_list[0];
            $user->save();
        }

        if ($request->roles_list) {
            $roles = Role::whereIn('id', $request->roles_list)->get();
            $user->syncRoles($roles);
        }

        if ($request->permissions_list) {
            $permissions = Permission::whereIn('id', $request->permissions_list)->get();
            $user->syncPermissions($permissions);
        }

        return response(['results' => $user, 'message' => 'User ' . ($request->id ? 'updated' : 'created') . ' successfully.']);
    }

    public function show($id)
    {
        return $this->index($id);
    }

    public function edit($id)
    {
        $user = $this->model::with('roles')->findOrFail($id);

        return response(['status' => true, 'results' => SearchRepo::of($user, [], [], ['name', 'email'])]);
    }

    public function profileUpdate(Request $request)
    {

        $user = $this->model::find(auth()->user()->id);
        $request->validate([
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            $datePath = Carbon::now()->format('Y/m/d');
            $avatarPath = $request->file('avatar')->store('users/' . $datePath);
            $user->avatar = $avatarPath;
        }

        $user->save();

        $user = $this->model::find(auth()->user()->id);
        $user->token = $user->createToken("API TOKEN")->plainTextToken;

        $roles = $user->getRoleNames();
        $user->roles = $roles;
        return response(['type' => 'success', 'results' => $user, 'message' => 'User updated Successfully']);
    }

    public function updateSelfPassword()
    {

        request()->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|max:100|confirmed',
        ]);

        $user = $this->model::find(auth()->user()->id);

        $user->password = Hash::make(request('password'));
        $user->update();
        $password = request('password');

        $data = [
            'subject' => 'New Password For ' . config('app.name'),
            'message' => 'Your ' . config('app.name') . ' new password is a below',
            'password' => $password,
            'instruction' => 'Please use the password as it appears.',
            'user_name' => $user->name,
            'user_email' => $user->email,
        ];

        try {
            Mail::to($user->email)->send(new SendPassword($data));
        } catch (\Exception $e) {

            return response(['type' => 'error', 'message' => $e->getMessage()], 500);
        }

        return response(['type' => 'success', 'message' => 'Password updated Successfully']);
    }

    public function resendToken($userId)
    {

        $user = User::findOrFail($userId);
        request()->merge(['email' => $user->email]);

        request()->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            request()->only('email')
        );

        return response(['message' => Str::title(Str::replace('.', ' ', $status))]);
    }

    public function autoLoginUser($userId)
    {

        $user = $this->model::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->token = $user->createToken("API TOKEN")->plainTextToken;

        $roles = $user->getRoleNames();
        $user->roles = $roles;

        return response()->json([
            'message' => 'Logged In Successfully',
            'results' => $user,
        ], 200);
    }

    public function loginLogs()
    {
        $auth_log = AuthenticationLog::query();

        $results = SearchRepo::of($auth_log)
            ->addColumn('ip', fn($q) => $q->ip_address)
            ->addColumn('source', fn($q) => $q->user_agent)
            ->addColumn('time', fn($q) => $q->login_at ? Carbon::parse($q->login_at)->toDateTimeString() : '-')
            ->paginate();

        return response(['results' => $results]);
    }

    public function listAttemptedLogins()
    {
        $days = \request()->days ?? 30;
        $failedloginattempts = AuthenticationLog::leftjoin('users', 'authentication_log.authenticatable_id', 'users.id')
            ->select(
                'authentication_log.id',
                'authentication_log.authenticatable_type',
                'authentication_log.ip_address',
                'authentication_log.user_agent',
                'authentication_log.login_at as time_of_access',
                'authentication_log.logout_at',
                'authentication_log.login_successful as successful',
                'users.name as user'
            )->where('login_successful', '=', 0)->whereDate('authentication_log.login_at', '>=', Carbon::today()->subDays($days));


        if (\request()->tabs) {
            return [
                'failed_login_attempts' => $failedloginattempts->count()
            ];
        }

        return SearchRepo::of($failedloginattempts)
            ->addColumn('login_successful', function ($failedloginattempts) {
                if ($failedloginattempts->login_successful) {
                    $color = 'success';
                } else {
                    $color = 'danger';
                }
                return $color;
            })
            ->paginate();
    }

    /**
     * Logout the user and revoke the current access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            // Delete the current access token
            if ($user) {
                $user->currentAccessToken()->delete();
            }

            return response()->json([
                'status' => true,
                'message' => 'Logged Out Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
