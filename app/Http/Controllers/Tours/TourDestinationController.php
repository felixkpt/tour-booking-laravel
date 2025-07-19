<?php

namespace App\Http\Controllers\Tours;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use App\Repositories\Tour\TourDestination\TourDestinationRepositoryInterface;
use App\Services\Validations\Tour\TourDestination\TourDestinationValidationInterface;
use Illuminate\Http\Request;

class TourDestinationController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private TourDestinationRepositoryInterface $tourRepository,
        private TourDestinationValidationInterface $repoValidation,
    ) {
        $this->repo = $tourRepository;
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

    public function storeFromJson(Request $request)
    {
        $data = $this->repoValidation->storeFromJson($request);

        return $this->repo->storeFromJson($request, $data);
    }
}
