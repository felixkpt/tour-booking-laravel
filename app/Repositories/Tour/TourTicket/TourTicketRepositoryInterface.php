<?php

namespace  App\Repositories\Tour\TourTicket;

use App\Repositories\CommonRepoActionsInterface;
use Illuminate\Http\Request;

interface TourTicketRepositoryInterface extends CommonRepoActionsInterface
{

    public function index();

    public function store(Request $request, $data);
    
    public function show($id);
}
