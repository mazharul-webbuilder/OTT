<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterSendRequest;
use App\Jobs\NewsLetterSendJob;
use App\Models\NewsLetter;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Query\Builder;

class NewsLetterController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:newsletter-history|newsletter-create|newsletter-delete', ['only' => ['index']]);
        $this->middleware('permission:newsletter-create', ['only' => ['newsletterSend']]);
        $this->middleware('permission:newsletter-delete', ['only' => ['deleteNewsletter']]);

    }

    /**
     * Return Newsletter history
    */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 10);

            $query = NewsLetter::latest()->orderBy('id', 'DESC');

            if ($request->filled('query_string')){
                $query = getSearchQuery($query, $request->input('query_string'), 'subject');
            }
            return $this->successResponse('Data fetched successfully', $query->paginate($perPage));

        } catch (QueryException $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Send newsletter
    */
    public function newsletterSend(NewsletterSendRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['email_from'] = 'noreply@durbar.live';
            $data['count_number_of_users'] = $this->getNewsletterSendableUsersQuery()->count();

            $newsLetter = NewsLetter::create($data);

            $data['usersEmails'] = $this->getNewsletterSendableUsersQuery()->pluck('email')->toArray();
            $data['unsubscribe_page'] = config('app.newsletter_unsubscribe_url');

            dispatch(new NewsLetterSendJob($data));

            return $this->successResponse('Newsletter send successfully', $newsLetter);
        } catch (ValidationException|\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    /**
     * Delete newsletter
    */
    public function deleteNewsletter(string $id): JsonResponse
    {
        try {
            $newsletter = NewsLetter::findOrFail($id);

            $newsletter->delete();

            return $this->successResponse('Newsletter Deleted Successfully', null);

        } catch (EntityNotFoundException|\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get Active Users Query
    */
    private function getNewsletterSendableUsersQuery(): Builder
    {
        return DB::table('users')
            ->where('is_newsletter_subscriber', 1)
            ->where('account_status', 'Active')
            ->whereNotNull('email');
    }
}
