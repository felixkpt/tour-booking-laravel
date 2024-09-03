<?php

namespace App\Repositories\Tour;

use App\Models\Tour;
use App\Repositories\CommonRepoActions;
use App\Repositories\SearchRepo\SearchRepo;
use Illuminate\Http\Request;

class TourRepository implements TourRepositoryInterface
{
    use CommonRepoActions;

    function __construct(protected Tour $model) {}

    public function index()
    {

        $tours = $this->model::query()->with([
            'destination' => function ($query) {
                $query->with('imageSlides');
            },
            'creator',
            'status'
        ]);

        if (request()->all == '1')
            return response(['results' => $tours->get()]);

        $uri = '/admin/tours/';
        $tours = SearchRepo::of($tours, ['id', 'name'])
            ->setModelUri($uri)
            ->addColumn('Created_by', 'getUser')
            ->addFillable('tour_destination_id', ['input' => 'dropdown', 'type' => null, 'dropdownSource' => '/api/admin/destinations'], 'roles_multiplelist')
            ->paginate();

        return response(['results' => $tours]);
    }

    public function store(Request $request, $data)
    {

        $res = $this->autoSave($data);

        $action = 'created';
        if ($request->id)
            $action = 'updated';

        return response(['type' => 'success', 'message' => 'Tour ' . $action . ' successfully', 'results' => $res]);
    }

    public function show($id)
    {
        $tour = $this->model::with([
            'destination' => function ($query) {
                $query->with('imageSlides');
            },
            'creator',
            'status'
        ])->findOrFail($id);

        return response(['results' => $tour]);
    }
}
