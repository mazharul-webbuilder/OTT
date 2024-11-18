<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\OttNotification;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory;

class NotificationController extends Controller
{
    use ResponseTrait, Media;
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function postNotification(Request $request)
    {



        try {

            $rules = array(
                'title' => 'required',
                'body' => 'required',
                'image' => 'nullable|mimes:jpeg,png,jpg,gif',
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->validationError("Validation Error", null, $validator->errors());
            }

            if ($file = $request->file('image')) {
                $fileData = $this->uploads($file, 'notification');
                $request->image = $fileData['fileName'];
                // dd($request->image);
            }

            $notification = OttNotification::create([
                'title' => $request->title,
                'body' => $request->body,
                'user_id' => Auth::user()->id,
                'image' => $request->image ?? '',
            ]);

            $firebase = (new Factory)
                ->withServiceAccount(storage_path('firebase_credentials.json'));
            $messaging = $firebase->createMessaging();

            $message = CloudMessage::fromArray([
                'notification' => [
                    "title" => $request->title,
                    "body" => $request->body,
                    "image" => "$request->image",
                ],
                'topic' => 'ottDebug'
            ]);

            $response = $messaging->send($message);
            if ($response) {
                $notification->status = 1;
                $notification->save();
                return $this->successResponse('Notification Sent Successfully', null);
            } else {
                return  $this->errorResponse('error', "Something went wrong!");
            }
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null, null);
        }
    }

    public function getNotification(Request $request)
    {

        try {
            if ($request->has('per_page')) {
                $per_page = $request->per_page;
            } else {
                $per_page = 10;
            }
            $query = OttNotification::latest();

            // Search implement
            if ($request->filled('query_string')) {
                $query = getSearchQuery($query,   $request->input('query_string'),  'title', 'short_title');
            }

            return $this->successResponse('Notification fetch Successfully', $query->paginate($per_page));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function deleteNotification($id)
    {
        try {

            OttNotification::findOrFail($id)->delete();

            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}
