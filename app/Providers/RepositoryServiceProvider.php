<?php

namespace App\Providers;

use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Status\StatusRepository;
use App\Repositories\Status\StatusRepositoryInterface;
use App\Repositories\Tour\TourBooking\TourBookingRepository;
use App\Repositories\Tour\TourBooking\TourBookingRepositoryInterface;
use App\Repositories\Tour\TourDestination\TourDestinationRepository;
use App\Repositories\Tour\TourDestination\TourDestinationRepositoryInterface;
use App\Repositories\Tour\TourRepository;
use App\Repositories\Tour\TourRepositoryInterface;
use App\Repositories\Tour\TourTicket\TourTicketRepository;
use App\Repositories\Tour\TourTicket\TourTicketRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Repositories/helpers.php');

        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->singleton(TourRepositoryInterface::class, TourRepository::class);
        $this->app->singleton(TourBookingRepositoryInterface::class, TourBookingRepository::class);
        $this->app->singleton(TourDestinationRepositoryInterface::class, TourDestinationRepository::class);
        $this->app->singleton(TourTicketRepositoryInterface::class, TourTicketRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
