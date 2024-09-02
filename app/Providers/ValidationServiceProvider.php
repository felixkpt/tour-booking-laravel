<?php

namespace App\Providers;

use App\Services\Validations\Permission\PermissionValidation;
use App\Services\Validations\Permission\PermissionValidationInterface;
use App\Services\Validations\Role\RoleValidation;
use App\Services\Validations\Role\RoleValidationInterface;
use App\Services\Validations\Status\StatusValidation;
use App\Services\Validations\Status\StatusValidationInterface;
use App\Services\Validations\Tour\TourBooking\TourBookingValidation;
use App\Services\Validations\Tour\TourBooking\TourBookingValidationInterface;
use App\Services\Validations\Tour\TourDestination\TourDestinationValidation;
use App\Services\Validations\Tour\TourDestination\TourDestinationValidationInterface;
use App\Services\Validations\Tour\TourTicket\TourTicketValidation;
use App\Services\Validations\Tour\TourTicket\TourTicketValidationInterface;
use App\Services\Validations\Tour\TourValidation;
use App\Services\Validations\Tour\TourValidationInterface;
use App\Services\Validations\User\UserValidation;
use App\Services\Validations\User\UserValidationInterface;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RoleValidationInterface::class, RoleValidation::class);
        $this->app->bind(PermissionValidationInterface::class, PermissionValidation::class);
        $this->app->bind(UserValidationInterface::class, UserValidation::class);
        $this->app->bind(StatusValidationInterface::class, StatusValidation::class);
        $this->app->bind(TourValidationInterface::class, TourValidation::class);
        $this->app->bind(TourBookingValidationInterface::class, TourBookingValidation::class);
        $this->app->bind(TourDestinationValidationInterface::class, TourDestinationValidation::class);
        $this->app->bind(TourTicketValidationInterface::class, TourTicketValidation::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {}
}
