<?php

namespace  App\Repositories\Tour\TourDestination;

use App\Repositories\CommonRepoActionsInterface;
use Illuminate\Http\Request;

interface TourDestinationRepositoryInterface extends CommonRepoActionsInterface
{

    public function index();
    public function store(Request $request, $data);
    public function storeFromJson(Request $request, $data);
    public function show($id);
}
