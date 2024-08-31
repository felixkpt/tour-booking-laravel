<?php

use App\Models\Status;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

if (!function_exists('defaultColumns')) {

    function defaultColumns($model)
    {

        if (Schema::hasColumn($model->getTable(), 'user_id') && !$model->user_id)
            $model->user_id = auth()->id() ?? 0;

        if (Schema::hasColumn($model->getTable(), 'status_id') && !$model->status_id)
            $model->status_id = activeStatusId();

        if (Schema::hasColumn($model->getTable(), 'uuid') && !$model->uuid)
            $model->uuid = Str::uuid();


        return true;
    }
}

if (!function_exists('wasCreated')) {

    function wasCreated($model)
    {
        return !$model->wasRecentlyCreated && $model->wasChanged() ? true : false;
    }
}

if (!function_exists('Created_at')) {
    function created_at($q)
    {
        return $q->created_at->diffForHumans();
    }
}

if (!function_exists('Created_by')) {
    function Created_by($q)
    {
        return getUser($q);
    }
}

if (!function_exists('getStatus')) {
    function getStatus($q)
    {
        $status = $q->status()->first();
        if ($status) {
            return '<div class="d-flex align-items-center"><iconify-icon icon="' . $status->icon . '" class="' . $status->class . ' me-1"></iconify-icon>' . Str::ucfirst(Str::replace('_', ' ', $status->name)) . '</div>';
        } else return null;
    }
}

if (!function_exists('getUser')) {
    function getUser($q)
    {
        $username = $q->user->name ?? 'System';
        return $username;
    }
}

if (!function_exists('activeStatusId')) {
    function activeStatusId()
    {
        return Status::where('name', 'active')->first()->id ?? 0;
    }
}

if (!function_exists('inActiveStatusId')) {
    function inActiveStatusId()
    {
        return Status::where('name', 'in_active')->first()->id ?? 0;
    }
}

if (!function_exists('getUriFromUrl')) {
    function getUriFromUrl($url)
    {
        // Parse the URL to get its components
        $parsedUrl = parse_url($url);

        // Extract the path from the parsed URL
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

        return $path;
    }
}

if (!function_exists('is_connected')) {
    function is_connected()
    {
        try {
            fopen("http://www.google.com:80/", "r");
            return true;
        } catch (Exception $e) {
            Log::critical('Internet connectivity issue: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('sanctum_auth')) {
    function sanctum_auth()
    {
        // Check if the request contains a Sanctum token
        if ($token = request()->bearerToken()) {
            // Attempt to find the token in the personal access tokens table
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken && $accessToken->tokenable) {
                // Token is valid, authenticate the user
                auth()->login($accessToken->tokenable);
            }
        }
    }
}
