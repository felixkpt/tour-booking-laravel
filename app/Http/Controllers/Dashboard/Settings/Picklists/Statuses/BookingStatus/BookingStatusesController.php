<?php

namespace App\Http\Controllers\Dashboard\Settings\Picklists\Statuses\BookingStatusRepository;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use App\Repositories\Status\BookingStatus\BookingStatusRepository;
use App\Services\Validations\Status\BookingStatus\BookingStatusValidationInterface;
use Illuminate\Http\Request;

class BookingStatusesController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private BookingStatusRepository $bookingStatusRepository,
        private BookingStatusValidationInterface $repoValidation,
    ) {
        $this->repo = $bookingStatusRepository;
    }

    public function index()
    {
        return $this->repo->index();
    }

    public function store(Request $request)
    {
        $data = $this->repoValidation->store($request);

        return $this->repo->store($request, $data);
    }
}
