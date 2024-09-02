<?php

namespace App\Http\Controllers\Tours;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use App\Repositories\Tour\TourBooking\TourBookingRepositoryInterface;
use App\Services\Validations\Tour\TourBooking\TourBookingValidationInterface;
use Illuminate\Http\Request;

class TourBookingController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private TourBookingRepositoryInterface $tourRepository,
        private TourBookingValidationInterface $repoValidation,
    ) {
        $this->repo = $tourRepository;
    }

    public function index()
    {
        return $this->repo->index();
    }

    public function getSelf()
    {
        return $this->repo->index(true);
    }

    public function store(Request $request)
    {
        $request->merge((['user_id' => auth()->id()]));
        $data = $this->repoValidation->store($request);

        return $this->repo->store($request, $data);
    }
}
