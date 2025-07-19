<?php

namespace  App\Repositories\Tour\TourBooking;

use App\Repositories\CommonRepoActionsInterface;
use Illuminate\Http\Request;

interface TourBookingRepositoryInterface extends CommonRepoActionsInterface
{

    public function index();

    public function store(Request $request, $data);
    
    public function show($id);
}
