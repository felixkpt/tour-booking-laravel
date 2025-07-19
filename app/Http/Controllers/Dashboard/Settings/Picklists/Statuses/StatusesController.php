<?php

namespace App\Http\Controllers\Dashboard\Settings\Picklists\Statuses;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use App\Repositories\Status\StatusRepositoryInterface;
use App\Services\Validations\Status\StatusValidationInterface;
use Illuminate\Http\Request;

class StatusesController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private StatusRepositoryInterface $statusRepository,
        private StatusValidationInterface $repoValidation,
    ) {
        $this->repo = $statusRepository;
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
