<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContentWatchHisotoryStoreRequest;
use App\Models\WatchHistory;
use App\Traits\ResponseTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ContentWatchHistoryController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {

    }



}
