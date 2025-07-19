<?php

namespace App\Repositories\Tour\TourDestination;

use App\Models\TourDestination;
use App\Models\User;
use App\Repositories\CommonRepoActions;
use App\Repositories\SearchRepo\SearchRepo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourDestinationRepository implements TourDestinationRepositoryInterface
{
    use CommonRepoActions;

    function __construct(protected TourDestination $model) {}

    public function index()
    {

        $tours = $this->model::query()->with((['creator', 'status', 'imageSlides']));

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
        if ($request->id) {
            $action = 'updated';
        }

        return response(['type' => 'success', 'message' => 'TourDestination ' . $action . ' successfully', 'results' => $res]);
    }

    public function storeFromJson(Request $request, $destinations)
    {

        ini_set('max_execution_time', 60 * 30);

        // Fetch users
        $users = User::all();
        $results = [];

        foreach ($destinations['json'] as $destination) {

            $category = $destination[0];
            $dest = $destination[1];
            foreach ($dest as $data) {

                $data['slug'] = Str::slug($data['name']);

                $data['category'] = Str::slug($category);
                $data['short_content'] = $data['shortContent'];
                $data['featured_image'] = $this->saveImageFromUrl($data['image']);
                $data['been_here'] = $data['beenHere'];
                $data['wants_to_count'] = $data['wantsToCount'];
                $data['added_to_list'] = $data['addedToList'];

                $exists = $this->model->where('name', $data['name'])->first();
                if (!$exists) {
                    $creator_id = null;
                    $random = rand(1, 4);
                    if ($random <= 3) {
                        // 75% chance to assign a creator
                        $creator_id = $users->random()->id;
                    }
                    $data['creator_id'] = $creator_id;
                    try {
                        DB::beginTransaction();
                        $result = $this->autoSave($data);
                        $result->imageSlides()->createMany(
                            collect($data['images'])->map(fn($url) => ['image_path' => $this->saveImageFromUrl($url)])->toArray()
                        );
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response([
                            'type' => 'error',
                            'message' => 'Failed to save tour destination' . $e->getMessage(),
                            'results' => $results
                        ]);
                    }

                    $results[] = $result;

                    sleep(5);
                }
            }
        }

        $saved = count($results);
        return response([
            'type' => 'success',
            'message' => "Tour {$saved} destinations saved successfully",
            'results' => $results
        ]);
    }

    public function saveImageFromUrl($url)
    {
        if (!$url) {
            return null;
        }


        try {
            $response = Http::withoutVerifying()->get($url);

            if ($response->successful()) {
                $extension = pathinfo($url, PATHINFO_EXTENSION) ?: 'jpg';
                $filename = 'uploaded-images/' . Str::random(40) . '.' . $extension;
                Storage::disk('public')->put($filename, $response->body());

                return $filename;
            }
        } catch (\Exception $e) {
            logger()->error('Failed to fetch and save image: ' . $e->getMessage());
        }

        return null;
    }

    public function show($id)
    {
        $tour = $this->model::with((['creator', 'status', 'imageSlides']))->findOrFail($id);
        return response(['results' => $tour]);
    }
}
