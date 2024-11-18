<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OttContent;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UpcomingContentController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:upcoming-contents', ['only' => ['getUpcomingContents']]);
    }

    /**
     * Return list of upcoming content
    */
    public function getUpcomingContents(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 10);

            // Default Query
            $query = OttContent::select('id', 'uuid', 'title', 'thumbnail_portrait', 'thumbnail_landscape','release_date')
                ->with('contentSource')
                ->whereDate('release_date', '>=', Carbon::now())
                ->where('status', '!=', 'Published')
                ->orderBy('release_date', 'ASC');

            // Add Filter to Query
            if ($request->filled('filter')) {
                switch ($request->input('filter')) {
                    case 'daily':
                        // Filter for daily content
                        $query = $query->whereDate('release_date', '=', Carbon::now()->startOfDay());
                        break;
                    case 'weekly':
                        // Filter for weekly content
                        $query = $query->whereBetween('release_date', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ]);
                        break;
                    case 'monthly':
                        // Filter for monthly content
                        $query = $query->whereBetween('release_date', [
                            Carbon::now()->startOfMonth(),
                            Carbon::now()->endOfMonth()
                        ]);
                        break;
                    default:
                        return $this->errorResponse("Invalid filter Parameter: " . $request->input('filter'), null);
                }
            }

            // Search implement
            if ($request->filled('query_string')) {
                $query = getSearchQuery($query, $request->input('query_string'), 'title');
            }

            // Filter By Date Range
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query = $query->whereBetween('release_date', [$request->input('start_date'), $request->input('end_date')]);
            }

            return $this->successResponse('Data fetched successfully', $query->paginate($perPage));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

}
