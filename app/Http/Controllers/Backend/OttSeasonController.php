<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\OttContent;
use App\Models\Season;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OttSeasonController extends Controller
{
    use Media, ResponseTrait;
    function __construct()
    {
        $this->middleware('permission:ott-slider-list|ott-slider-create|ott-slider-edit|ott-slider-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:ott-slider-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ott-slider-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:ott-slider-delete', ['only' => ['destroy']]);
    }

    /**
     *
     *
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

            $query = Season::with('series:id,title')->latest();

            if ($request->filled('query_string')) {
                $query = getSearchQuery($query, $request->input('query_string'),  'title');
            }
            return $this->successResponse('Data fetched Successfully', $query->paginate($per_page));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $rules = array(
            'ott_series_id' => 'required|numeric|exists:ott_series,id',
            'title' => 'required|string',
            'slug' => 'required|unique:seasons,slug',
            'status' => 'required|string|in:Pending,Hold,Published',
            'thumbnail' => 'mimes:jpeg,png,jpg,gif,webp',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->validationError("Validation Error", null, $validator->getMessageBag());
        }
        if ($file = $request->file('thumbnail')) {
            $path = $request->path;
            $fileData = $this->uploads($file, $path);
            $request->thumbnail = $fileData['fileName'];
        }

        try {
            $season = new Season();
            $season->title = $request->title;
            $season->thumbnail = $request->thumbnail;
            $season->slug = Str::slug($request->slug, '-');
            $season->status = $request->status;
            $season->ott_series_id = $request->ott_series_id;
            $season->save();

            return $this->successResponse('Data store successfully', $season);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Display the specified resource.
     */

    public function show($id): JsonResponse
    {
        try {
            $data = Season::findOrFail($id);

            return $this->successResponse('Data Fetch successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $season = Season::findOrFail($id);

            $rules = array(
                'ott_series_id' => 'required|numeric|exists:ott_series,id',
                'title' => 'required|string',
                'status' => 'required|string|in:Pending,Hold,Published',
                'slug' => [
                    'required',
                    Rule::unique('seasons', 'slug')->ignore($season->id),
                ],
                'thumbnail' => 'mimes:jpeg,png,jpg,gif,webp',
            );


            if ($file = $request->file('thumbnail')) {
                $path = $request->path;
                $fileData = $this->uploads($file, $path);
                $request->thumbnail = $fileData['fileName'];
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->validationError("Validation Error", null, $validator->getMessageBag());
            }


            $season->title = $request->title;
            if ($request->has('thumbnail')) {
                $season->thumbnail = $request->thumbnail;
            }
            $season->slug = Str::slug($request->slug, '-');
            $season->status = $request->status;
            $season->ott_series_id = $request->ott_series_id;
            $season->update();

            return $this->successResponse('Data updated successfully', $season);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            Season::findOrFail($id)->delete();
            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    /**
     * Filter Season for create Episodes
     */
    public function filterSeasons($id): JsonResponse
    {
        try {
            $seasons = Season::select('id', 'ott_series_id', 'title', 'slug', 'thumbnail')->where('ott_series_id', $id)->get();
            return $this->successResponse('Data Fetched successfully', $seasons);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Get NextEpisode Number
     */
    public function getEpisodeNumber($id): JsonResponse
    {
        try {
            $episode = OttContent::select('id', 'episode_number')->where('season_id', $id)->orderBy('episode_number', 'desc')->first();
            $episode_number = 0;
            if ($episode == null) {
                $episode_number = 1;
            } else {
                $episode_number = $episode->episode_number + 1;
            }
            return $this->successResponse('Data Fetched successfully', ['episode_number' => $episode_number]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Get NextEpisode Number
     */
    public function getEpisodes(Request $request): JsonResponse
    {
        try {
            $per_page = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

            $datas = OttContent::with(['ottSeries:id,title', 'ottSeason:id,title'])->where('series_id','!=','null')->latest()->paginate($per_page);
            return $this->successResponse('Data fetched Successfully', $datas);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}
