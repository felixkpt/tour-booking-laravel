<?php

namespace App\Repositories\Tour\TourBooking;

use App\Models\Tour;
use App\Models\TourBooking;
use App\Models\TourBookingStatus;
use App\Models\TourTicket;
use App\Repositories\CommonRepoActions;
use App\Repositories\SearchRepo\SearchRepo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $tours = SearchRepo::of($tours, ['id', 'name', 'tour.name', 'user.name'])
            ->statuses(TourBookingStatus::all()->toArray())
            ->setModelUri($uri)
            ->addColumn('Created_by', 'getUser')
            ->addFillable('user_id', ['input' => 'dropdown', 'type' => null, 'dropdownSource' => '/api/admin/settings/users'], 'tour_id')
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

    function updateStatus($id)
    {
        request()->validate(['status_id' => 'required']);

        $status_id = request()->status_id;
        $model = $this->model::findorFail($id);

        // if Completed cancel request prevent
        if (in_array($model->status->name, ['Completed', 'Cancelled'])) {
            abort(422, 'Tour status can no longer be updated.');
        }

        // if Confirmed | Confirmed and cancel request prevent
        if (in_array($model->status->name, ['Confirmed', 'Confirmed'])) {
            abort(422, 'The tour can no longer be canceled.');
        }

        try {
            DB::beginTransaction();
            $newStatus = TourBookingStatus::find($status_id);

            // if request is cancel increment the tour slots by the number of current booking
            if ($newStatus->name == 'Cancelled') {
                $model->tour->update(['slots' => $model->tour->slots + $model->slots]);
            }
        
            $model->update(['status_id' => $status_id]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::info('Tour booking status update failure:' . $e->getMessage());
        }

        return response(['message' => "Status updated successfully."]);
    }
}
