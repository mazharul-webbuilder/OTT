<?php

namespace App\Http\Controllers\BackendLaravel;


use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\ContentSource;
use App\Models\OttContent;
use App\Models\OttContentMeta;
use App\Models\OttContentSubtitle;
use App\Models\OttContentTrailer;
use App\Models\OttSeries;
use App\Models\OttSlider;
use App\Models\RootCategory;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSVideoFilters;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use ProtoneMedia\LaravelFFMpeg\Support\MediaOpenerFactory;

class OttContentController extends Controller
{
    use Media, ResponseTrait;
    
    function __construct()
    {
        $this->middleware('permission:ott-content-list|ott-content-create|ott-content-edit|ott-content-delete|ott-content-upload-media-page|ott-content-upload-media|ott-media-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:ott-content-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ott-content-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:ott-content-delete', ['only' => ['destroy']]);
        $this->middleware('permission:ott-content-upload-media-page', ['only' => ['getUploadMedia']]);
        $this->middleware('permission:ott-content-upload-media', ['only' => ['uploadMedia']]);
        $this->middleware('permission:ott-media-delete', ['only' => ['deleteContentSource']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ott_contents = OttContent::with('rootCategory')->orderBy('order', 'asc')->orderBy('id', 'asc')->get();

        return view('admin.pages.ott_content.index', compact('ott_contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $root_categories = RootCategory::orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.pages.ott_content.create')->with('root_categories', $root_categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = array(
            'title' => 'required',
            // 'poster_image' => 'required|mimes:jpeg,png,jpg,gif',
            'root_category_id' => 'required|numeric',
            'status' => 'required',
            'access' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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
            OttContentMeta::insert($ottContentMetaData);

            return redirect()->route('ott-content.show', $ottContentId)->with('message', 'Data Inserted Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
        // dd($id);
        $root_categories = RootCategory::get();
        $data = OttContent::where('id', $id)->with('ottContentMeta', 'ottSeries', 'contentSource', 'rootCategory', 'subCategory', 'subSubCategory', 'contentSource')->first();
        return view('admin.pages.ott_content.edit', compact('root_categories'))->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

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
                // file not uploaded
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
                // delete chunked file
                unlink($file->getPathname());
                $video = $fileName;
                $encoded_video = $path.'/'.Str::slug($ottContent_title).'.m3u8';
            $encoded_video_path = env('DO_SPACES_PUBLIC') . $encoded_video;

            $lowFormat  = (new X264('aac'))->setKiloBitrate(500);
            $highFormat = (new X264('aac'))->setKiloBitrate(1000);
            
            FFMpeg::fromDisk('spaces')
                // ->open('video.mp4')
                ->open($filePath)
                ->exportForHLS()
                ->addFormat($lowFormat, function (HLSVideoFilters $filters) {
                    $filters->resize(1280, 720);
                })
                ->addFormat($highFormat)
                
                ->toDisk('spaces')
                ->save($encoded_video);

            $video2 = $encoded_video_path;

            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '1080p'
            ];
            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '720p'
            ];
            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '360p'
            ];
            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '244p'
            ];
            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
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

            return redirect()->back()->with('message', 'Data Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
            OttContent::find($id)->delete();
            return back()->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }


    public function filterSubSubCategory($id)
    {
        $sub_sub_categories = SubSubCategory::where('sub_category_id', $id)->get();
        return response()->json(['sub_sub_categories' => $sub_sub_categories, 'success' => 1], 200);
    }
    public function filterSubSubCategoryWithContent($id)
    {
        $sub_sub_categories = SubSubCategory::where('sub_category_id', $id)->get();
        $contents = OttContent::where('status', 'Published')->where('sub_category_id', $id)->get();
        return response()->json(['sub_sub_categories' => $sub_sub_categories, 'contents' => $contents, 'success' => 1], 200);
    }
    public function filterSubSubCategoryContent($id)
    {
        $contents = OttContent::where('status', 'Published')->where('sub_sub_category_id', $id)->get();
        return response()->json(['contents' => $contents, 'success' => 1], 200);
    }
    public function filterSeries(Request $request)
    {
        if ($request->sub_sub_category_id == null) {
            $serieses = OttSeries::where('root_category_id', $request->root_category_id)->where('sub_category_id', $request->sub_category_id)->where('status', 'Published')->get();
        } else if ($request->sub_sub_category_id == null && $request->sub_category_id  == null) {

            $serieses = OttSeries::where('root_category_id', $request->root_category_id)->where('status', 'Published')->get();
        } else {
            $serieses = OttSeries::where('root_category_id', $request->root_category_id)->where('sub_category_id', $request->sub_category_id)->where('sub_sub_category_id', $request->sub_sub_category_id)->where('status', 'Published')->get();
        }
        return response()->json(['serieses' => $serieses, 'success' => 1], 200);
    }
    public function uploadMedia(Request $request)
    {
        // dd($request->all());
        $ottContent_title = OttContent::where('id', $request->content_id)->pluck('title')->first();
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

            //this is for cloud ser uoload....
            $path = '/OTT/Content/' . Str::slug($ottContent_title) . '/video';
            $filePath = Storage::disk('spaces')->putFile($path, $file, 'public');
            $fileName = env('DO_SPACES_PUBLIC') . $filePath;
            //this is for cloud ser uoload....
            // delete chunked file
            unlink($file->getPathname());
            $video = $fileName;
            $encoded_video = $path.'/'.Str::slug($ottContent_title).'.m3u8';
            $encoded_video_path = env('DO_SPACES_PUBLIC') . $encoded_video;

            $lowFormat  = (new X264('aac'))->setKiloBitrate(500);
            $highFormat = (new X264('aac'))->setKiloBitrate(1000);

            FFMpeg::fromDisk('spaces')
                // ->open('video.mp4')
                ->open($filePath)
                ->exportForHLS()
                ->addFormat($lowFormat, function (HLSVideoFilters $filters) {
                    $filters->resize(1280, 720);
                })
                ->addFormat($highFormat)
                
                ->toDisk('spaces')
                ->save($encoded_video);

            $video2 = $encoded_video_path;

            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '1080p'
            ];
            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '720p'
            ];
            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '360p'
            ];
            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '244p'
            ];
            $data[] = [
                'ott_content_id' => $request->content_id,
                'content_source' => $video2,
                'fps' => '20',
                'source_format' => '140p'
            ];
            ContentSource::insert($data);
        }

        // otherwise return percentage informatoin
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
    public function getUploadMedia($id)
    {
        return view('admin.pages.ott_content.upload_media')->with('content_id', $id);
    }
    public function getUploadMediaSubtitle($id)
    {
        return view('admin.pages.ott_content.upload_media_subtitle')->with('content_id', $id);
    }
    public function storeUploadMediaSubtitle(Request $request)
    {


        if ($file_poster = $request->file('file')) {
            $path = "/OTT/Content/Subtitles";
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $final_name = time() . "subtitle" . "." . $ext;
            $filePath =  $file->storeAs($path, $final_name, 'spaces');
            // $filePath = Storage::disk('spaces')->putFile($path, $file, 'public');
            $request['subtitle'] = env('DO_SPACES_PUBLIC') . $filePath;
            dd($request['subtitle']);
        }
        OttContentSubtitle::create($request->except(['file', '_token']));
        return redirect()->route('ott-content.show', $request->content_id);
    }

    public function getUploadTrailer($id)
    {
        return view('admin.pages.ott_content.upload_media_trailer')->with('content_id', $id);
    }
    public function uploadTrailer(Request $request)
    {
        ini_set('max_execution_time', 1800);
        // dd(11);
        $ottContent_title = OttContent::where('id', $request->content_id)->pluck('title')->first();
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.' . $extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

            //this is for cloud ser uoload....
            $path = '/OTT/Content/' . Str::slug($ottContent_title).time() . '/Trailer/video';
            $filePath = Storage::disk('spaces')->putFile($path, $file, 'public');
            $fileName = env('DO_SPACES_PUBLIC') . $filePath;
            //this is for cloud ser uoload....
            // delete chunked file

            

            

            unlink($file->getPathname());
            $video = $fileName;
            
            $encoded_video = $path.'/'.Str::slug($ottContent_title).'.m3u8';
            $encoded_video_path = env('DO_SPACES_PUBLIC') . $encoded_video;

            $lowFormat  = (new X264('aac'))->setKiloBitrate(500);
            $highFormat = (new X264('aac'))->setKiloBitrate(1000);

            FFMpeg::fromDisk('spaces')
                // ->open('video.mp4')
                ->open($filePath)
                ->exportForHLS()
                ->addFormat($lowFormat, function (HLSVideoFilters $filters) {
                    $filters->resize(1280, 720);
                })
                ->addFormat($highFormat)
                
                ->toDisk('spaces')
                ->save($encoded_video);

            $video2 = $encoded_video_path;
            $data = [
                'content_id' => $request->content_id,
                'title' => $ottContent_title,
                'trailer' => $video2,
            ];
            
            // dd($data);

            OttContentTrailer::create($data);
        }

        // otherwise return percentage informatoin
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
    public function deleteContentSource($id)
    {
        $content_source = ContentSource::find($id);
        $path = '/OTT/Content/alice-for-some/video/vE3cXcyA8fEUZHV1QpAT9Bc7c1HToGTr48mWnsCH.mp4';
        if (Storage::disk('spaces')->exists($path)) {
            Storage::disk('spaces')->delete($path);
        }
        $content_source->delete();
        return back();
    }
}
