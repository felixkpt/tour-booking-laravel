<?php

namespace App\Repositories\Tour\TourBooking;

use App\Models\Tour;
use App\Models\TourBooking;
use App\Models\TourTicket;
use App\Repositories\CommonRepoActions;
use App\Repositories\SearchRepo\SearchRepo;
use Illuminate\Http\Request;

class TourBookingRepository implements TourBookingRepositoryInterface
{
    use CommonRepoActions;

    function __construct(protected TourBooking $model) {}

    public function index()
    {

        $tours = $this->model::query()->with(([
            'user',
            'tour',
            'status',
            'ticket',
            'creator',
            'status'
        ]));

        if (request()->all == '1')
            return response(['results' => $tours->get()]);

        $uri = '/admin/tours/bookings';
        $tours = SearchRepo::of($tours, ['id', 'name'])
            ->setModelUri($uri)
            ->addColumn('Created_by', 'getUser')
            ->addFillable('tour_id', ['input' => 'dropdown', 'type' => null, 'dropdownSource' => '/api/admin/tours'], 'roles_multiplelist')
            ->addFillable('destination_id', ['input' => 'dropdown', 'type' => null, 'dropdownSource' => '/api/admin/destinations'], 'roles_multiplelist')
            ->paginate();

        return response(['results' => $tours]);
    }

    public function store(Request $request, $data)
    {

        $res = $this->autoSave($data);

        $action = 'created';
        if ($request->id)
            $action = 'updated';

        // If creating a new tour booking, create a new ticket
        if (!$request->id) {
            // Example data for ticket creation - adjust as needed
            $ticketData = [
                'tour_booking_id' => $res->id,
                'ticket_number' => generateTicketNumber(),
                'creator_id' => auth()->id(),
                'status_id' => 1,
            ];

            TourTicket::create($ticketData);

            $tour = Tour::find($data['tour_id']);
            // Calculate new slots value
            $newSlots = $tour->slots - $data['slots'];

            // Update slots to zero if the new value is negative
            $tour->update(['slots' => max($newSlots, 0)]);
        }

        return response(['type' => 'success', 'message' => 'TourBooking ' . $action . ' successfully', 'results' => $res]);
    }

    public function show($id)
    {
        $tour = $this->model::with(([
            'user',
            'tour',
            'status',
            'ticket',
            'creator',
            'status'
        ]))->findOrFail($id);
        
        return response(['results' => $tour]);
    }
}
