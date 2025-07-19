<?php

namespace  App\Repositories\Tour;

use App\Repositories\CommonRepoActionsInterface;
use Illuminate\Http\Request;

interface TourRepositoryInterface extends CommonRepoActionsInterface
{

    public function index();

    public function store(Request $request, $data);
    
    public function show($id);
}
