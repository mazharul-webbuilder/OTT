<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OttContent;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use App\Traits\ResponseTrait;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class ReportController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {

    }

    /**
     * Subscription Report
    */
    public function getSubscriptionReport(Request $request): JsonResponse
    {
        try {
            $query = SubscriptionPlan::select(['id', 'plan_name', 'plan_slug']);

            // Filter data by key
            if ($request->filled('filter')){
                $filterBy = (string) $request->input('filter');
                switch ($filterBy){
                    case "today":
                        $query = $query->withCount(['userSubscriptions' => function($q){
                            $q->whereDate('created_at', '=', Carbon::now()->startOfDay());
                        }]);
                        break;
                    case "week":
                        $query = $query->withCount(['userSubscriptions' => function($q){
                            $q->whereBetween('created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        }]);
                        break;
                    case "month":
                        $query = $query->withCount(['userSubscriptions' => function($q){
                            $q->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                        }]);
                        break;
                    case "year":
                        $query = $query->withCount(['userSubscriptions' => function($q){
                            $q->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                        }]);
                        break;
                    default:
                        return $this->errorResponse("Invalid query string", null);
                }
            } elseif ($request->filled('start_date') && $request->filled('end_date')) {
                // Filter by Date range
                $query = $query->withCount(['userSubscriptions' => function($q) use($request){
                    $q->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
                }]);
            } else {
                // Default
                $query = $query->withCount('userSubscriptions');
            }
            // data
            $data = $query->paginate($request->input('per_page', DB::table('subscription_plans')->count()));
            // add new filed, value to the collection
            $data->put('totalSubscription', $data->sum('user_subscriptions_count'));

            return $this->successResponse("Data fetched successfully", $data);
        } catch (\Exception | QueryException $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get user reports with subscription info
    */
    public function getUserReport(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', DB::table('users')->count());

            $accountStatus = $request->input('account_status', 'Active');

            $query = User::where('account_status', $accountStatus)->select(['phone', 'account_status']);

            $data = $query->paginate($perPage);

            return $this->successResponse('Data fetched successfully', $data);
        } catch (QueryException|\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Return Subscription and Payment Report
    */
    public function getSubscriptionNdPaymentReport(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);

            // Basic Query with eager loading
            $query = UserSubscription::with([
                'paymentWithBinaryStatus',
                'user:id,name,email,phone,avatar,gender,account_status,created_at as join_date',
                'subscriptionPlan:id,plan_name,description,status,is_renewable'
            ])->select('id', 'subscription_plan_id', 'user_id', 'start_date', 'end_date', 'duration', 'price', 'is_active', 'created_at as subscription_date');

            // Manage Search
            if ($request->filled('query_string')){
                $queryString = $request->input('query_string');

                $query = $query->whereHas('user', function ($q) use ($queryString) {
                    $q->where('name', 'like', '%' . $queryString . '%')
                        ->orWhere('email', 'like', '%' . $queryString . '%')
                        ->orWhere('phone', 'like', '%' . $queryString . '%');
                });
            }
            // Filter Data by date range if provided
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
            }
            // Get subscription counts
            $totalSubscription = $this->countTotalSubscriber();
            $currentMonthSubscription = $this->queryCurrentMonth('user_subscriptions')->where('is_active', '=',1)->count();
            $previousMonthSubscription = $this->queryPreviousMonth('user_subscriptions')->where('is_active', '=',1)->count();
            // Take current month start and previous month start and end
            $currentMontStart = Carbon::now()->startOfMonth();
            $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            // free users report
            $freeUsers = User::whereDoesntHave('subscriptionPlans')->count();
            $currentMonthFreeUsers = User::whereDoesntHave('subscriptionPlans')->where('created_at', '>=', $currentMontStart)->count();
            $previousMonthFreeUsers = User::whereDoesntHave('subscriptionPlans')->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
            // Get Paid user counts
            $paidUsers = User::whereHas('subscriptionPlans')->count();
            $currentMonthPaidUsers = User::whereHas('subscriptionPlans')->where('created_at', '>=', $currentMontStart)->count();
            $previousMonthPaidUsers = User::whereHas('subscriptionPlans')->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();

            // Set response data
            $responseData = [
                'subscription_and_payment' => $query->paginate($perPage),
                'total_subscription' => $totalSubscription,
                'current_month_subscription' => $currentMonthSubscription,
                'previous_month_subscription' => $previousMonthSubscription,
                'subscription_percentage_change' => $this->percentageChange($previousMonthSubscription, $currentMonthSubscription),
                'free_users' => $freeUsers,
                'paid_users' => $paidUsers,
                'current_month_free_user' => $currentMonthFreeUsers,
                'previous_month_free_user' => $previousMonthFreeUsers,
                'free_user_percentage_change' => $this->percentageChange($previousMonthFreeUsers, $currentMonthFreeUsers),
                'current_month_paid_user' => $currentMonthPaidUsers,
                'previous_month_paid_user' => $previousMonthPaidUsers,
                'paid_user_percentage_change' => $this->percentageChange($previousMonthPaidUsers, $currentMonthPaidUsers)
            ];

            return $this->successResponse('Data fetched successfully', $responseData);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get Trending Content list
    */
    public function getTrendingContentReport(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            // Basic Query
            $query = OttContent::whereHas('userActivitySync')->withCount('userActivitySync')
                ->orderBy('user_activity_sync_count', 'desc');
            // filter data
            if ($request->filled('start_date') && $request->filled('end_date')){
                $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
            }
            // search manage
            if ($request->filled('query_string')){
             $query = getSearchQuery( $query,  $request->input('query_string'),  'uuid', 'title', 'short_title') ;
            }
            // Set response data
            $responseData = [
                'trending_contents' => $query->paginate($perPage),
                'total_content' => $this->countTotalContent(),
                'total_pending_content' => $this->countTotalContent(status: 'Pending'),
                'total_hold_content' => $this->countTotalContent(status: 'Hold'),
                'total_published_content' => $this->countTotalContent(status: 'Published'),
                'total_subscriber' => $this->countTotalSubscriber(),
            ];
            return $this->successResponse('Data fetched successfully', $responseData);

        } catch (QueryException | \Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
    /**
     * Return contents with total watch time
    */
    public function getContentWatchHistoryReport(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            // Basic Query
            $query = OttContent::whereHas('userActivitySync')
                ->withSum('userActivitySync', 'last_watch_time')
                ->orderBy('user_activity_sync_sum_last_watch_time', 'DESC');
            // Filter Data
            if ($request->filled('start_date') && $request->filled('end_date')){
                $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
            }
            // Search Manage
            if ($request->filled('query_string')){
                $query = getSearchQuery( $query,  $request->input('query_string'),  'uuid', 'title', 'short_title') ;
            }
            // Get collection
            $ottContents = $query->paginate($perPage);
            // Add total watched hours
            $ottContents->each(function ($content) {
                $totalWatchedSeconds = (float) $content->user_activity_sync_sum_last_watch_time;

                // Initialize variables for hours and minutes
                $hours = floor($totalWatchedSeconds / 3600); // 3600 seconds in an hour
                $totalWatchedSeconds %= 3600; // Get remaining seconds after calculating hours
                $minutes = floor($totalWatchedSeconds / 60); // 60 seconds in a minute

                // Format the result
                $formattedTime = "$hours Hours";

                // If there are minutes, add them to the formatted string
                if ($minutes > 0) {
                    $formattedTime .= " $minutes Minutes";
                }

                // Assign the formatted time to the total_watched property of the content
                $content->total_watched = $formattedTime;
            });

            // Set response data
            $responseData = [
                'contents' => $ottContents,
                'total_contents' => $this->countTotalContent(),
                'total_subscriber' => $this->countTotalSubscriber(),
            ];

            return $this->successResponse('Data fetched successfully', $responseData);
        } catch (QueryException|\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }

    }
    /**
     * Calculate percentage Change
    */
    private function percentageChange($previousMonthSubscription, $currentMonthSubscription): string
    {
        // Calculate the percentage change
        if ($previousMonthSubscription > 0) {
            return  Number::percentage((($currentMonthSubscription - $previousMonthSubscription) / $previousMonthSubscription) * 100);
        } else {
            // Handle the case where the previous month subscription count is zero to avoid division by zero
            return Number::percentage($currentMonthSubscription > 0 ? 100 : 0);
        }
    }

    /**
     * query current Month
    */
    private function queryCurrentMonth(string $table): Builder
    {
        $currentMonthStart = Carbon::now()->startOfMonth();

        return DB::table($table)
            ->where('created_at', '>=', $currentMonthStart);
    }

    /**
     * query previous mont
    */
    private function queryPreviousMonth(string $table): Builder
    {
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        return DB::table($table)->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd]);
    }

    /**
     * return total subscriber
    */
    public function countTotalSubscriber(): int
    {
        return DB::table('user_subscriptions')->where('is_active', '=',1)->count();
    }

    /**
     * Return total content
    */
    public function countTotalContent($status = null): int
    {
        $query = DB::table('ott_contents');

        return isset($status) ?
            $query->where('status', '=', $status)->count()
            :
            $query->count();
    }
}
