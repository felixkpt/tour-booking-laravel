<?php

use Felixkpt\Nestedroutes\Http\Middleware\NestedroutesAuthMiddleware;
use Felixkpt\Nestedroutes\RoutesHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Nested Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Nested routes for your application. These
| routes are loaded by the NesetedRouteServiceProvider within a group which
| can be assigned the "web" or "api" middleware group. Enjoy building your App!
|
*/

$nested_routes_folder = config('nestedroutes.folder');
// Prefix all generated routes
$prefix = config('nestedroutes.prefix') ?? 'api';
// Middlewares to be passed before accessing any route
$middleWares = config('nestedroutes.middleWares');
$middleWares = $middleWares && count($middleWares) > 0 ? $middleWares : 'api';

Route::middleware([])
    ->prefix($prefix)
    ->group(function () use ($nested_routes_folder) {

        $routes_path = base_path('routes/' . $nested_routes_folder);

        if (file_exists($routes_path)) {
            $route_files = collect(File::allFiles($routes_path))->filter(function ($file) {
                $filename = $file->getFileName();
                return !Str::is($filename, 'driver.php') && !Str::is($filename, 'auth.route.php') && Str::endsWith($filename, '.php');
            });

            foreach ($route_files as $file) {

                $res = (new RoutesHelper(''))->handle($file);

                $prefix = $res['prefix'];
                $file_path = $res['file_path'];

                Route::prefix($prefix)->group(function () use ($file_path) {
                    require $file_path;
                });
            }
        }
    });
