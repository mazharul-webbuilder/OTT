<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests\MarketplaceRequest;
use App\Http\Requests\MarketplaceUpdateRequest;
use App\Http\Resources\MarketplaceResource;
use App\Models\Marketplace;
use App\Models\OttContent;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class MarketplaceController extends Controller
{
    use ResponseTrait, Media;

    public function __construct()
    {
        $this->middleware('permission:marketplace-list|marketplace-create|marketplace-edit|marketplace-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:marketplace-create', ['only' => ['store']]);
        $this->middleware('permission:marketplace-edit', ['only' => ['update']]);
        $this->middleware('permission:marketplace-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

            if ($request->filled('status')) {
                $datas = Marketplace::where('status', $request->input('status'))->with(['ottContents'])->paginate($perPage);
            } else {
                $datas = Marketplace::with(['ottContents'])->paginate($perPage);
            }
            return $this->successResponse('Data fetched successfully', $datas);

        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarketplaceRequest $request): JsonResponse
    {
        try {
            $marketplace = Marketplace::create($request->validated());
            $this->uploadMarketplaceIcon($request, $marketplace);
            return $this->successResponse('Marketplace stored successfully', MarketplaceResource::make($marketplace));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $marketplace = Marketplace::find($id);

        if (is_null($marketplace)) {
            return $this->errorResponse('Invalid id', null);
        }
        return $this->successResponse('Data fetched successfully', MarketplaceResource::make($marketplace));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(MarketplaceUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $marketplace = Marketplace::find($id);

            if (is_null($marketplace)) {
                return $this->errorResponse("Invalid id", null);
            }

            $marketplace->status = (int) $request->input('status');

            $marketplace->update($request->validated());

            $this->uploadMarketplaceIcon($request, $marketplace);

            return $this->successResponse("Data Updated successfully",MarketplaceResource::make($marketplace));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        if (!is_null($marketplace = Marketplace::find($id))) {
            $marketplace->delete();
            return $this->successResponse('Data deleted successfully', null);
        } else {
            return $this->errorResponse("Invalid id", null);

        }
    }

    /**
     * Upload marketplace icon image
    */
    private function uploadMarketplaceIcon($request, $marketplace): void
    {
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $path = $request->path(); // api/v1/admin/marketplace
            $fileData = $this->uploads($file, $path);
            $marketplace->icon = $fileData['fileName'];
            $marketplace->save();
        }
    }

    /**
     * Return all content with available marketplaces on it
    */
    public function getContentWithAvailableMarketplaces(Request $request): JsonResponse
    {
        $query = OttContent::with('marketplaces', 'contentSource');

        $perPage = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

        return $this->successResponse('Data fetched successfully', $query->select('id', 'uuid', 'title', 'short_title')->paginate($perPage));
    }
}
