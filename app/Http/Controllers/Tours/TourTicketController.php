<?php

namespace App\Http\Controllers\Tours;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use App\Repositories\Tour\TourTicket\TourTicketRepositoryInterface;
use App\Services\Validations\Tour\TourTicket\TourTicketValidationInterface;
use Illuminate\Http\Request;

class TourTicketController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private TourTicketRepositoryInterface $tourRepository,
        private TourTicketValidationInterface $repoValidation,
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