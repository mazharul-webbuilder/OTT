<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\OttContentResource;
use App\Jobs\ConvertVideoForStreaming;
use App\Models\ContentSource;
use App\Models\OttContent;
use App\Models\OttContentMeta;
use App\Models\SubSubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Elasticsearch;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\Dimension;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class OttContentController extends Controller
{
    use Media, ResponseTrait;
     public function __construct()
     {
         $this->middleware('permission:ott-content-list|ott-content-create|ott-content-edit|ott-content-delete|ott-content-upload-media-page|ott-content-upload-media|ott-media-delete', ['only' => ['index', 'show']]);
         $this->middleware('permission:ott-content-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:ott-content-edit', ['only' => ['show', 'update']]);
         $this->middleware('permission:ott-content-delete', ['only' => ['destroy']]);
         $this->middleware('permission:ott-content-upload-media-page', ['only' => ['getUploadMedia']]);
         $this->middleware('permission:ott-content-upload-media', ['only' => ['uploadContentFile']]);
         $this->middleware('permission:ott-media-delete', ['only' => ['deleteContentSource']]);
     }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->input('per_page', 10);

            $data = OttContent::latest()->paginate($per_page);
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Get lite OTT Contents
    */
    public function getLiteOttContentList(Request $request): JsonResponse
    {
        try {
            $queryString = $request->input('query_string');

            if (strlen($queryString) <= 1){
                throw new Exception('Query string is too short');
            }
            $query = OttContent::select('id', 'uuid', 'title', 'poster','thumbnail_portrait','thumbnail_landscape');

            $query = getSearchQuery($query, $queryString, 'title');

            return $this->successResponse('Data fetched successfully', $query->take(50)->get());

        }catch (QueryException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'root_category_id' => 'required|numeric',
            'status' => 'required',
            'access' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);


        if ($file_poster = $request->file('poster_image')) {

            $path = "/OTT/Content/Posters";
            $fileData = $this->uploads($file_poster, $path);
            $request['poster'] = $fileData['fileName'];
        }
        if ($file_backdrop = $request->file('backdrop_image')) {

            $path = "/OTT/Content/Backdrops";
            $fileData = $this->uploads($file_backdrop, $path);
            $request['backdrop'] = $fileData['fileName'];
        }


        try {
            $ottContentData = [
                'title' => $request->title,
                'uuid' => Str::uuid(),
                'short_title' => $request->short_title,
                'root_category_id' => $request->root_category_id,
                'sub_category_id' => $request->sub_category_id,
                'sub_sub_category_id' => $request->sub_sub_category_id,
                'content_type_id' => $request->content_type_id,
                'series_id' => $request->series_id,
                'series_order' => $request->series_order,
                'order' => $request->order,
                'description' => $request->description,
                'release_date' => $request->release_date,
                'runtime' => $request->runtime,
                'cloud_url' => $request->cloud_url,
                'youtube_url' => $request->youtube_url,
                'status' => $request->status,
                'access' => $request->access,
                'poster' => $request->poster,
                'backdrop' => $request->backdrop,
                'is_live_stream' => $request->is_live_stream,
            ];

            $ottContentId = OttContent::insertGetId($ottContentData);
            $ottContentMetaData = [];
            foreach ($request->key as $index => $item) {
                $ottContentMetaData[] = [
                    'key' => $item,
                    'value' => $request['value'][$index],
                    'content_id' => $ottContentId
                ];
            }


            //Update To Elastic
            $ott_content = OttContent::find($ottContentId);
            if ("Published" == $ott_content->status) {
                $ott_content->addToIndex();
            }

            OttContentMeta::insert($ottContentMetaData);
            return $this->successResponse('Data inserted Successfully', $ottContentData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = new  OttContentResource(OttContent::findOrFail($id));
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ottContent = OttContent::find($id);
        $rules = array(
            'title' => 'required',
            'root_category_id' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($file = $request->file('video')) {
            $receiver = new FileReceiver('video', $request, HandlerFactory::classFromRequest($request));

            if (!$receiver->isUploaded()) {

            }

            $fileReceived = $receiver->receive(); // receive file
            if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
                $file = $fileReceived->getFile(); // get file
                $extension = $file->getClientOriginalExtension();
                $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
                $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

                // $disk = Storage::disk('spaces');
                $path = '/OTT/Content/' . Str::slug($request->title) . '/video';
                $filePath = Storage::disk('spaces')->putFile($path, $file, 'public');
                $fileName = env('DO_SPACES_PUBLIC') . $filePath;
                unlink($file->getPathname());
                $video = $fileName;
                $data[] = [
                    'ott_content_id' => $id,
                    'content_source' => $video,
                    'fps' => '20',
                    'source_format' => '1080p'
                ];
                $data[] = [
                    'ott_content_id' => $id,
                    'content_source' => $video,
                    'fps' => '20',
                    'source_format' => '720p'
                ];
                $data[] = [
                    'ott_content_id' => $id,
                    'content_source' => $video,
                    'fps' => '20',
                    'source_format' => '360p'
                ];
                $data[] = [
                    'ott_content_id' => $id,
                    'content_source' => $video,
                    'fps' => '20',
                    'source_format' => '244p'
                ];
                $data[] = [
                    'ott_content_id' => $id,
                    'content_source' => $video,
                    'fps' => '20',
                    'source_format' => '140p'
                ];
                ContentSource::insert($data);
            }
        }

        if ($file_poster = $request->file('poster_image')) {

            $path = "/OTT/Content/Posters";
            $fileData = $this->uploads($file_poster, $path);
            $request['poster'] = $fileData['fileName'];
        } else {
            $request->poster = $ottContent->poster;
        }
        if ($file_backdrop = $request->file('backdrop_image')) {

            $path = "/OTT/Content/Backdrops";
            $fileData = $this->uploads($file_backdrop, $path);
            $request['backdrop'] = $fileData['fileName'];
        } else {
            $request->backdrop = $ottContent->backdrop;
        }
        try {
            $ottContentData = [
                'title' => $request->title,
                'uuid' => Str::uuid(),
                'short_title' => $request->short_title,
                'root_category_id' => $request->root_category_id,
                'sub_category_id' => $request->sub_category_id,
                'sub_sub_category_id' => $request->sub_sub_category_id,
                'content_type_id' => $request->content_type_id,
                'series_id' => $request->series_id,
                'series_order' => $request->series_order,
                'order' => $request->order,
                'description' => $request->description,
                'release_date' => $request->release_date,
                'runtime' => $request->runtime,
                'cloud_url' => $request->cloud_url,
                'youtube_url' => $request->youtube_url,
                'status' => $request->status,
                'access' => $request->access,
                'poster' => $request->poster,
                'backdrop' => $request->backdrop,
            ];

            OttContent::where('id', $id)->update($ottContentData);
            $ottContentMetaData = [];
            foreach ($request->key as $index => $item) {
                $ottContentMetaData = [
                    'key' => $item,
                    'value' => $request['value'][$index],
                    'content_id' => $id
                ];
                OttContentMeta::updateOrCreate(['content_id' => $id], $ottContentMetaData);
            }

            //Update To Elastic
            if ("Published" == $request->status) {
                $ottContent->addToIndex();
            } else {
                $ottContent->addToIndex();
                $ottContent->removeFromIndex();
            }

            return $this->successResponse('Data updated Successfully', $ottContentData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = OttContent::find($id);
            if ($data != null) {
                $data->delete();
                return $this->successResponse('Data deleted Successfully', null);
            }
            return $this->successResponse('Content Not found with this id', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
    public function uploadContentFile(Request $request)
    {
        ini_set('max_execution_time', 18000);
        $video = OttContent::where('id', $request->content_id)->first();
        $folderName = 'videos/' . $video->uuid;

        Storage::disk('public')->makeDirectory($folderName);

        $path = $request->file->store('videos/' . $video->uuid, 'public');


        $this->dispatch(new ConvertVideoForStreaming($video, $path));
        $stream_url = Storage::disk('public')->url($video->id . '/' . $video->id . '.m3u8');
        $content_source = ContentSource::where('ott_content_id', $video->id)->where('source_format', '720p')->first();
        if ($content_source != null) {
            ContentSource::where('id', $content_source->id)->update([
                'content_source' => $stream_url
            ]);
        } else {
            ContentSource::create([
                'ott_content_id' => $video->id,
                'source_format' => '720p',
                'content_source' => $stream_url,
                'fps' => 60
            ]);
        }

        return response()->json([
            'id' => $video->id,
            'stream_url' => $stream_url,
            'uuid' => $video->uuid
        ], 201);
    }

    public function contentProcessStatus($content_id)
    {
        $content_source = ContentSource::firstOrNew(
            ['ott_content_id' => $content_id],
            ['source_format' => '720p']
        );
        return $this->successResponse("Process Status", $content_source->processing_status);
    }


    // public function uploadContentFile(Request $request)
    // {
    //     // return $request->all();
    //     // dd($request->all());
    //     $ottContent_title = OttContent::where('id', $request->content_id)->pluck('title')->first();
    //     $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

    //     if (!$receiver->isUploaded()) {
    //         // file not uploaded
    //     }

    //     $fileReceived = $receiver->receive(); // receive file
    //     // if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
    //     //     $file = $fileReceived->getFile(); // get file
    //     //     $extension = $file->getClientOriginalExtension();
    //     //     $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
    //     //     $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

    //     //     $disk = Storage::disk(config('filesystems.default'));
    //     //     $path = $disk->putFileAs('videos', $file, $fileName);

    //     //     // delete chunked file
    //     //     unlink($file->getPathname());
    //     //     return [
    //     //         'path' => asset('storage/' . $path),
    //     //         'filename' => $fileName
    //     //     ];
    //     // }
    //     if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
    //         $file = $fileReceived->getFile(); // get file
    //         $extension = $file->getClientOriginalExtension();
    //         $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
    //         $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

    //         //this is for cloud ser uoload....
    //         $path = '/OTT/Content/' . Str::slug($ottContent_title) . '/video';
    //         $filePath = Storage::disk('spaces')->putFile($path, $file, 'public');
    //         $fileName = env('DO_SPACES_PUBLIC') . $filePath;
    //         //this is for cloud ser uoload....
    //         // delete chunked file
    //         unlink($file->getPathname());
    //         $video = $fileName;
    //         $data[] = [
    //             'ott_content_id' => $request->content_id,
    //             'content_source' => $video,
    //             'fps' => '20',
    //             'source_format' => '1080p'
    //         ];
    //         // $data[] = [
    //         //     'ott_content_id' => $request->content_id,
    //         //     'content_source' => $video,
    //         //     'fps' => '20',
    //         //     'source_format' => '720p'
    //         // ];
    //         // $data[] = [
    //         //     'ott_content_id' => $request->content_id,
    //         //     'content_source' => $video,
    //         //     'fps' => '20',
    //         //     'source_format' => '360p'
    //         // ];
    //         // $data[] = [
    //         //     'ott_content_id' => $request->content_id,
    //         //     'content_source' => $video,
    //         //     'fps' => '20',
    //         //     'source_format' => '244p'
    //         // ];
    //         // $data[] = [
    //         //     'ott_content_id' => $request->content_id,
    //         //     'content_source' => $video,
    //         //     'fps' => '20',
    //         //     'source_format' => '140p'
    //         // ];
    //         ContentSource::insert($data);
    //         // return [
    //         //     'path' => asset('storage/' . $path),
    //         //     'filename' => $fileName
    //         // ];
    //         // return redirect()->route('ott-content.show', $request->content_id);
    //     }

    //     // otherwise return percentage informatoin
    //     $handler = $fileReceived->handler();
    //     // dd(asset('storage/' . $path));
    //     return [
    //         'done' => $handler->getPercentageDone(),
    //         'status' => true
    //     ];
    // }

    public function deleteContentSource($id)
    {
        $content_source = ContentSource::find($id);

        $path = '/OTT/Content/alice-for-some/video/vE3cXcyA8fEUZHV1QpAT9Bc7c1HToGTr48mWnsCH.mp4';

        if (Storage::disk('spaces')->exists($path)) {
            // dd(2);
            Storage::disk('spaces')->delete($path);
        }
        try {

            if ($content_source->delete()) {
                return $this->successResponse('Data deleted Successfully', null);
            }
            return $this->successResponse('Content Not found with this id', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    public function createOttcontentIndex(Request $request)
    {
        try {
            $data = OttContent::createIndex($shards = null, $replicas = null);

            return $this->successResponse('Index Created Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    public function addOttContentsToIndex(Request $request)
    {

        try {
            \Illuminate\Support\Facades\Artisan::call('index:contents');

            return $this->successResponse('Data Added Successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function ottContentDeleteIndex()
    {
        try {
            Elasticsearch::indices()->delete(['index' => 'ott_contents']);
            return $this->successResponse('Index Deleted Successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }



    public function filterSubSubCategory($id)
    {
        try {
            $sub_sub_categories = SubSubCategory::where('sub_category_id', $id)->get();

            return $this->successResponse('Data Fetched successfully', $sub_sub_categories);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function filterSubSubCategoryWithContent($id)
    {

        try {
            $sub_sub_categories = SubSubCategory::where('sub_category_id', $id)->get();
            $contents = OttContent::where('status', 'Published')->where('sub_category_id', $id)->get();
            $data['sub_sub_categories'] = $sub_sub_categories;
            $data['contents']
                = $contents;

            return $this->successResponse('Data Fetched successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function filterSubSubCategoryContent($id)
    {

        try {
            $contents = OttContent::where('status', 'Published')->where('sub_sub_category_id', $id)->get();
            return $this->successResponse('Data Fetched successfully', $contents);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Get all pending sources
     */
    public function encodingPendingSource()
    {
        // $pendingContents = ContentSource::where('processing_status', 'pending')
        //     ->where(function ($query) {
        //         $query->where('source_type', 'content_raw_path')
        //             ->orWhere('source_type', 'trailer_raw_path');
        //     })
        //     ->get();

            $pendingContents = ContentSource::latest()->take(30)->get();
        return response()->json($pendingContents);
    }

    /**
     * Update a content source status
     */
    public function encodingStatusUpdate(Request $request)
    {
        $source = ContentSource::where('key', $request->name)->first();
        $source->processing_status = $request->status;
        $source->update();
        return $this->successResponse('Source updated successfully', $source);
    }

    /**
     * Get a single encoding content source
     */
    public function encodingSingleSource(Request $request){
        $source = ContentSource::where('key', $request->name)->where('processing_status','pending')->first();
        return $this->successResponse('Source updated successfully', $source);
    }
}
