<?php

namespace  App\Repositories\Status\BookingStatus;

use App\Repositories\CommonRepoActionsInterface;
use Illuminate\Http\Request;

interface BookingStatusRepositoryInterface extends CommonRepoActionsInterface
{

    public function index();

    public function store(Request $request, $data);
    
    public function show($id);
}
