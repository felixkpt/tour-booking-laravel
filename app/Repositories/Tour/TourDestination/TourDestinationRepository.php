<?php

namespace App\Repositories\Tour\TourDestination;

use App\Models\TourDestination;
use App\Repositories\CommonRepoActions;
use App\Repositories\SearchRepo\SearchRepo;
use Illuminate\Http\Request;

class TourDestinationRepository implements TourDestinationRepositoryInterface
{
    use CommonRepoActions;

    function __construct(protected TourDestination $model)
    {
    }

    public function index()
    {

        $tours = $this->model::query()->with((['creator', 'status']));

        if (request()->all == '1')
            return response(['results' => $tours->get()]);

        $uri = '/admin/tours/destinations';
        $tours = SearchRepo::of($tours, ['id', 'name'])
            ->setModelUri($uri)
            ->addColumn('Created_by', 'getUser')
            ->paginate();

        return response(['results' => $tours]);
    }

    public function store(Request $request, $data)
    {

        $res = $this->autoSave($data);

        $action = 'created';
        if ($request->id)
            $action = 'updated';

        return response(['type' => 'success', 'message' => 'TourDestination ' . $action . ' successfully', 'results' => $res]);
    }

    public function show($id)
    {
        $tour = $this->model::findOrFail($id);
        return response(['results' => $tour]);
    }
}
