<?php

namespace App\Repositories\Status\BookingStatus;

use App\Models\BookingStatus;
use App\Repositories\CommonRepoActions;
use App\Repositories\SearchRepo\SearchRepo;
use Illuminate\Http\Request;

class BookingStatusRepository implements BookingStatusRepositoryInterface
{
    use CommonRepoActions;

    function __construct(protected BookingStatus $model)
    {
    }

    public function index()
    {

        $statuses = $this->model::query();

        if (request()->all == '1')
            return response(['results' => $statuses->get()]);

        $uri = '/admin/settings/picklists/statuses/booking-statuses/';
        $statuses = SearchRepo::of($statuses, ['id', 'name'])
            ->setModelUri($uri)
            ->addColumn('Created_by', 'getUser')
            ->paginate();

        return response(['results' => $statuses]);
    }

    public function store(Request $request, $data)
    {

        $res = $this->autoSave($data);

        $action = 'created';
        if ($request->id)
            $action = 'updated';

        return response(['type' => 'success', 'message' => 'Status ' . $action . ' successfully', 'results' => $res]);
    }

    public function show($id)
    {
        $status = $this->model::findOrFail($id);
        return response(['results' => $status]);
    }
}
