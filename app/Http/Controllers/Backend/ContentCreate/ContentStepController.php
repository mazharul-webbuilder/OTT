<?php

namespace App\Http\Controllers\Backend\ContentCreate;

use App\Enums\ContentRawSourceType;
use App\Enums\ContentSourceType;
use App\Enums\ContentStatus;
use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContentCoutryRestrictictedRequest;
use App\Http\Requests\ContentDownloadableInfoRequest;
use App\Http\Requests\ContentMarketplaceUpdateRequest;
use App\Http\Resources\Backend\ContentCastCrewResource;
use App\Http\Resources\ContentMarketplaceResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\MarketplaceResource;
use App\Http\Resources\OttDownloadableContentResource;
use App\Http\Resources\SingleOttContentResource;
use App\Models\ContentOwners;
use App\Models\ContentSource;
use App\Models\Marketplace;
use App\Models\OttContent;
use App\Models\OttDownloadableContent;
use App\Rules\CustomValidationRule;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class ContentStepController extends Controller
{
    use ResponseTrait, Media;

    /**
     * Return OttContent data
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->input('per_page', 10);

            $contentType = $request->input('content_type', 'single'); // single or series

            $query = OttContent::where('is_live_stream', 0)->latest();

            if ($contentType == 'series') {
                $query = $query->whereNotNull('series_id')->with(['ottSeries:id,title,status', 'ottSeason:id,title,thumbnail,status']); // all series contents
            } else {
                $query = $query->whereNull('series_id'); // all single contents
            }

            // Search implement
            if ($request->filled('query_string'))
            {
                $query = getSearchQuery( $query,   $request->input('query_string'),  'title', 'short_title');
            }
            return $this->successResponse('Data fetched Successfully', $query->paginate($per_page));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * return OttContent data
     */
    public function getStreams(Request $request): JsonResponse
    {
        try {
            $per_page = $request->input('per_page', 10);

            $query = OttContent::with(['rootCategory:id,title','subCategory:id,title'])->where('is_live_stream',1)->latest();

            // Search implement
            if ($request->filled('query_string'))
            {
                $query = getSearchQuery( $query,   $request->input('query_string'),  'title', 'short_title');
            }
            return $this->successResponse('Data fetched Successfully', $query->paginate($per_page));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Return Single Content with Basic Info by Content UUID
    */
    public function getGeneralStepData($uuid): JsonResponse
    {
        try {
            $ottContentData =  OttContent::where('uuid', $uuid)->first([
                'title', 'title_bangla', 'content_type', 'video_type', 'vod_type',
                'upload_date', 'release_date', 'status',
                'associated_teaser', 'up_comming', 'imdb', 'is_original',
                'synopsis_english', 'synopsis_bangla', 'genre', 'tags', 'root_category_id', 'content_owner_id', 'uuid', 'external_id', 'sub_category_id', 'saga', 'runtime', 'is_live_stream', 'cloud_url','series_id','season_id','episode_number','live_stream_url','is_tvod_available'
            ]);
            if ($ottContentData != null) {
                return $this->successResponse('Data Fetched Successfully', ['general_content' => $ottContentData]);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Store General Step Data
    */
    public function storeGeneralStepData(Request $request): JsonResponse
    {
        $rules = array(
            'title' => 'required',
            'root_category_id' => 'required|numeric',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        }
        DB::beginTransaction();
        try {

            if ($request->has('content_owner_id')) {
                $content_owner_data = ContentOwners::where('id', $request->content_owner_id)->first();
                if ($content_owner_data != null) {
                    $request['external_id'] = str_replace(' ', '-', strtolower($content_owner_data->title)) . '-' . time();
                } else {
                    return $this->errorResponse('No Content Owner Found!', null);
                }
            }
            $request['uuid'] = Str::uuid();

            $ottContentData =  OttContent::create($request->all());
            DB::commit();
            return $this->successResponse('Data inserted Successfully', ['uuid' => $ottContentData->uuid]);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function updateGeneralStepData(Request $request, $uuid)
    {
        $rules = array(
            'title' => 'required',
            'root_category_id' => 'required|numeric',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag(), 422);
        };
        DB::beginTransaction();
        try {
            if ($request->has('uuid')) {
                return $this->errorResponse('UUID can not be updated', null);
            }
            if ($request->has('id')) {
                return $this->errorResponse('id can not be updated', null);
            }
            if ($request->has('content_owner_id')) {
                $content_owner_data = ContentOwners::where('id', $request->content_owner_id)->first();
                if ($content_owner_data != null) {
                    $request['external_id'] = str_replace(' ', '-', strtolower($content_owner_data->title)) . '-' . time();
                } else {
                    return $this->errorResponse('No Content Owner Found!', null);
                }
            }

            $ottContentData =  OttContent::where('uuid', $uuid)->first();
            if ($ottContentData != null) {
                $ottContent = OttContent::find($ottContentData->id);
                $ottContent->update($request->all());
                DB::commit();
                return $this->successResponse('Data updated Successfully', ['uuid' => $ottContentData->uuid]);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    public function updateCastCrewStepData(Request $request, $uuid)
    {

        if (!$uuid) {
            return response()->json("UUID cannot be null", 422);
        }
        $jsonData = $request->json()->all();

        // Define validation rules
        $rules = [
            'cast_and_crews_id' => 'required|integer',
            'role' => 'required|string',
        ];

        // Validate each JSON item
        foreach ($jsonData as $item) {
            $validator = Validator::make($item, $rules);

            if ($validator->fails()) {
                return response()->json($validator->getMessageBag(), 422);
            }
        }

        DB::beginTransaction();
        try {
            $content = OttContent::with('castAndCrew')->where('uuid', $uuid)->first();

            $syncData = [];

            foreach ($jsonData as $item) {
                $castAndCrewId = $item['cast_and_crews_id'];
                $role = $item['role'];

                // Prepare data for synchronization
                $syncData[$castAndCrewId] = ['role' => $role, 'cast_and_crew_id' => $castAndCrewId];
            }

            $content->castAndCrew()->sync($syncData);


            //Get Content With Pivot
            $content = OttContent::with('castAndCrew')->findOrFail($content->id);
            DB::commit();
            return $this->successResponse('Data Updated Successfully', ['uuid' => $content->uuid, 'content' => $content]);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function getCastCrewStepData($uuid)
    {
        try {

            $ottContentData = OttContent::with('castAndCrew')->where('uuid', $uuid)->first();
            $filterContentData = new ContentCastCrewResource($ottContentData);
            if ($ottContentData != null) {
                return $this->successResponse('Data Fetched Successfully', ['content' => $filterContentData]);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function updateMediaStepData(Request $request, $uuid)
    {

        $jsonData = $request->json()->all();
        // dd($jsonData);
        try {
            // Define validation rules
            $rules = [
                'content_source' => 'required|string',
                'source_type' => [new Enum(ContentSourceType::class)],
            ];

            // Validate each JSON item
            foreach ($jsonData as $item) {
                $validator = Validator::make($item, $rules);

                if ($validator->fails()) {
                    return response()->json($validator->getMessageBag(), 422);
                }
            }
            $ottContentData = OttContent::select('id', 'uuid')->where('uuid', $uuid)->first();
            if ($ottContentData != null) {
                foreach ($jsonData as $value) {
                    $data = $value;
                    $data['ott_content_id'] = $ottContentData->id;
                    $content = ContentSource::updateOrCreate(
                        ['ott_content_id' => $ottContentData->id, 'source_type' => $data['source_type']],
                        $data
                    );

                    //Make hls format url and save it content source
                    if ($content) {
                        if ("trailer_raw_path" == $data['source_type']) {
                            $data['content_source'] = $this->getCloudFrontHlsPath($data);
                            $data['source_type'] = ContentSourceType::TRAILER_PATH->value;
                            $data['processing_status'] = 'encoded';
                            ContentSource::updateOrCreate(
                                ['ott_content_id' => $ottContentData->id, 'source_type' => $data['source_type']],
                                $data
                            );
                        } elseif ("content_raw_path" == $data['source_type']) {
                            $data['content_source'] = $this->getCloudFrontHlsPath($data);
                            $data['source_type'] = ContentSourceType::CONTENT_PATH->value;
                            $data['processing_status'] = 'encoded';
                            ContentSource::updateOrCreate(
                                ['ott_content_id' => $ottContentData->id, 'source_type' => $data['source_type']],
                                $data
                            );
                        }
                    }
                }
                return $this->successResponse('Data Updated Successfully', $content);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Get content marketplaces
     */
    public function getContentMarketplaces(string $uuid): JsonResponse
    {
        try {
            if ($data = OttContent::with(['marketplaces'])->where('uuid', $uuid)->first()) {
                return $this->successResponse("Data fetched successfully", ContentMarketplaceResource::make($data));
            } else {
                return $this->errorResponse("Invalid UUID", null);
            }
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Update content marketplace data
     */
    public function updateContentMarketplaces(ContentMarketplaceUpdateRequest $request, string $uuid): JsonResponse
    {
        try {
            if ($content = OttContent::where('uuid', $uuid)->first()) {
                $marketplaceIds = json_decode($request->input('marketplace_ids'));
                $content->marketplaces()->sync($marketplaceIds);
                return $this->successResponse('Success', ContentMarketplaceResource::make($content));
            } else {
                return $this->errorResponse("Invalid UUID", null);
            }
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * get all countries
     */
    public function getAllCountries(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', DB::table('countries')->count());

        $countries = DB::table('countries')->paginate($perPage);

        return $this->successResponse("Data fetched successfully", $countries);
    }

    /**
     * get content restricted countries
     */
    public function getContentRestrictedCountries(string $uuid): JsonResponse
    {
        try {
            $content = OttContent::where('uuid', $uuid)->firstOrFail();

            if (is_null($content->restricted_countries_ids)) {
                return $this->successResponse('Content has no restriction', null);
            } else {
                $countryIds = json_decode($content->restricted_countries_ids);

                $restrictedCountries = DB::table('countries')->select('*')->whereIn('id', $countryIds)->get();

                return $this->successResponse('Data Fetched successfully', $restrictedCountries);
            }

        } catch (ModelNotFoundException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Update Content restricted country
     */
    public function updateContentRestrictedCountries(ContentCoutryRestrictictedRequest $request, string $uuid): JsonResponse
    {
        try {
            $content = OttContent::where('uuid', $uuid)->first();

            if (is_null($content)) {
                return $this->errorResponse('Invalid uuid', null);
            }
            if ($request->filled('no_restriction')) {
                $content->restricted_countries_ids = null;
                $content->save();
                return $this->successResponse('Data updated successfully', null);
            } else {
                $restrictedCountriesIds = json_decode($request->input('country_ids'));
                $content->restricted_countries_ids = json_encode($restrictedCountriesIds);
                $content->save();
                $restrictedCountries = DB::table('countries')->select('id', 'name')->whereIn('id', $restrictedCountriesIds)->get();

                return $this->successResponse('Data updated successfully', $restrictedCountries);
            }
        } catch (QueryException $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get content downloadable info
     */
    public function getContentDownloadableInfo(string $uuid): JsonResponse
    {
        try {
            $content = OttContent::where('uuid', $uuid)->firstOrFail();

            if ($content->is_downloadable) {
                $contentDownloadableInfo = OttDownloadableContent::where('ott_content_id', $content->id)->first();

                $marketplaces = Marketplace::whereIn('id', json_decode($contentDownloadableInfo->available_marketplace_ids))->get();

                $downloadable_qualities = json_decode($contentDownloadableInfo->downloadable_qualities);

                $data = [
                    'ott_content_id' => $contentDownloadableInfo->ott_content_id,
                    'content_uuid' => $content->uuid,
                    'expire_in_days' => $contentDownloadableInfo->expire_in_days,
                    'marketplaces' => MarketplaceResource::collection($marketplaces),
                    'downloadable_qualities' => $downloadable_qualities
                ];

                return $this->successResponse("Data fetched successfully", $data);
            }
            return $this->successResponse('Content is not downloadable', null);
        } catch (Exception|ModelNotFoundException $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * set a content downloadable info
     */
    public function setContentDownloadableInfo(ContentDownloadableInfoRequest $request, string $uuid): JsonResponse
    {
        try {
            $data = [];
            $content = OttContent::GetContentByUUID($uuid);
            if (is_null($content)) {
                return $this->errorResponse('Invalid uuid', null);
            }
            DB::beginTransaction();
            // store into DB if is_downloadable allow
            if ($request->input('is_downloadable')) {
                $marketplaceIdsDecode = json_decode($request->input('available_marketplace_ids'));
                $marketplaceIds = json_encode($marketplaceIdsDecode);
                $downloadableQualitiesDecode = json_decode($request->input('downloadable_qualities'));
                $downloadableQualities = json_encode($downloadableQualitiesDecode);
                $data = OttDownloadableContent::updateOrCreate(
                    ['ott_content_id' => $content->id],
                    [
                        'expire_in_days' => $request->input('expire_in_days'),
                        'available_marketplace_ids' => $marketplaceIds,
                        'downloadable_qualities' => $downloadableQualities,
                    ]
                );
            }
            $content->is_downloadable = $request->input('is_downloadable');
            $content->save();
            DB::commit();
            return $this->successResponse('Data updated successfully', $data);
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    public function updateGraphicStepData(Request $request, $uuid)
    {
        try {

            $rules = array(
                'thumbnail_landscape' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:10000',
                'thumbnail_portrait' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:10000',
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->getMessageBag(), 422);
            };
            // $data['thumbnail_landscape'] = '';
            // $data['thumbnail_portrait'] = '';
            if ($file = $request->file('thumbnail_landscape')) {
                $path = 'Images';
                $fileData = $this->uploads($file, $path);
                $data['thumbnail_landscape'] = $fileData['fileName'];
            }

            if ($file = $request->file('thumbnail_portrait')) {
                $path = 'Images';
                $fileData = $this->uploads($file, $path);
                $data['thumbnail_portrait'] = $fileData['fileName'];
            }
            $ottContentData = OttContent::select('id', 'uuid', 'thumbnail_landscape', 'thumbnail_portrait')->where('uuid', $uuid)->first();

            if ($ottContentData != null) {
                $ottContentData->update($data);
                return $this->successResponse('Data Updated Successfully', null);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    public function  getGraphicStepData($uuid)
    {
        try {

            $ottContentData = OttContent::select('thumbnail_portrait', 'thumbnail_landscape')->where('uuid', $uuid)->first();
            if ($ottContentData != null) {
                return $this->successResponse('Graphics Step Data  Fetch Successfully!', $ottContentData);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function updatePlayerEventStepData(Request $request, $uuid)
    {
        try {

            $rules = array(
                'intro_starts' => 'nullable',
                'intro_end' => 'nullable',
                'next_end' => 'nullable',
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->getMessageBag(), 422);
            };

            $ottContentData = OttContent::select('id', 'uuid', 'intro_starts', 'intro_end', 'next_end')->where('uuid', $uuid)->first();

            if ($ottContentData != null) {
                $ottContentData->update($request->all());
                return $this->successResponse('Data Updated Successfully', null);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function getPlayerEventStepData(Request $request, $uuid)
    {
        try {

            $ottContentData = OttContent::select('id', 'uuid', 'intro_starts', 'intro_end', 'next_end')->where('uuid', $uuid)->first();

            if ($ottContentData != null) {

                return $this->successResponse('Data Updated Successfully', $ottContentData);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function publishContent(Request $request, $uuid)
    {
        try {
            $ottContentData = OttContent::where('uuid', $uuid)->first();
            $ottContentData->status = ContentStatus::PUBLISHED->value;
            $ottContentData->update();
            if ($ottContentData != null) {

                return $this->successResponse('Content Published', $ottContentData);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function showContent($uuid)
    {
        try {

            $ottContentData = OttContent::with(['castAndCrew', 'contentSource', 'rootCategory', 'subCategory', 'contentOwner'])->where('uuid', $uuid)->first();

            $ottContentData = OttContent::with(['castAndCrew', 'contentSource','rootCategory','subCategory','contentOwner'])->where('uuid', $uuid)->first();

            if ($ottContentData != null) {
                return $this->successResponse('Content Fetch Successfully!', $ottContentData);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function generateFileNames(Request $request, $uuid)
    {
        $rules = array(
            'fileType' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse('validation error', $validator->getMessageBag(), 422);
        };

        try {
            $ottContentData = OttContent::select('id', 'uuid')->where('uuid', $uuid)->first();
            if ($ottContentData != null) {
                $sources = ContentRawSourceType::getAllValues();
                foreach ($sources as $source) {
                    $data = [
                        "source_type" => $source,
                        "key" => Str::ulid().'.'.$request->fileType,
                        "ott_content_id" => $ottContentData->id,
                        "processing_status" => 'missing'
                    ];
                    ContentSource::updateOrCreate(
                        ['ott_content_id' => $ottContentData->id, 'source_type' => $source, 'processing_status' => 'missing'],
                        $data
                    );
                }
                $content_sources = ContentSource::where(['ott_content_id' => $ottContentData->id, 'processing_status' => 'missing'])->get();
                return $this->successResponse('Name Generated Successfully!', $content_sources);
            } else {
                return $this->errorResponse('No Content Found with this uuid!', null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}
