<?php

namespace App\Http\Controllers\Tours;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use App\Repositories\Tour\TourRepositoryInterface;
use App\Services\Validations\Tour\TourValidationInterface;
use Illuminate\Http\Request;

class ToursController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private TourRepositoryInterface $tourRepository,
        private TourValidationInterface $repoValidation,
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
}
