<?php

namespace App\Repositories\Tour\TourTicket;

use App\Models\TourTicket;
use App\Repositories\CommonRepoActions;
use App\Repositories\SearchRepo\SearchRepo;
use Illuminate\Http\Request;

class TourTicketRepository implements TourTicketRepositoryInterface
{
    use CommonRepoActions;

    function __construct(protected TourTicket $model) {}

    public function index()
    {

        $tours = $this->model::query()->with(([
            'tourBooking' => fn($q) => $q->with('tour'),
            'creator',
            'status'
        ]));

        if (request()->all == '1')
            return response(['results' => $tours->get()]);

        $uri = '/admin/tours/tickets';
        $tours = SearchRepo::of($tours, ['id', 'name'])
            ->setModelUri($uri)
            ->addColumn('Created_by', 'getUser')
            ->addFillable('tour_booking_id', ['input' => 'dropdown', 'type' => null, 'dropdownSource' => '/api/admin/tours/bookings'], 'roles_multiplelist')
            ->paginate();

        return response(['results' => $tours]);
    }

    public function store(Request $request, $data)
    {

        $res = $this->autoSave($data);

        $action = 'created';
        if ($request->id)
            $action = 'updated';

        return response(['type' => 'success', 'message' => 'TourTicket ' . $action . ' successfully', 'results' => $res]);
    }

    public function show($id)
    {
        $tour = $this->model::with(([
            'tourBooking' => fn($q) => $q->with('tour'),
            'creator',
            'status'
        ]))->findOrFail($id);
        
        return response(['results' => $tour]);
    }
}
