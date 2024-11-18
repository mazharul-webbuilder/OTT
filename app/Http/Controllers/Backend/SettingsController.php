<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlatformSettingUpdateOrCreateRequest;
use App\Models\Setting;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    use ResponseTrait, Media;

    public function __construct()
    {

    }

    /**
     * get settings
    */
    public function getSettings(Request $request): JsonResponse
    {
        // Validation start
        $rules = [
            'keys' => ['nullable', 'array'],
            'keys.*' => [Rule::exists('settings', 'key'), 'distinct'],
            'key_list' => ['nullable', Rule::in([1])]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }
        // Validation end
        try {
            /*Return list of keys*/
            if ($request->filled('key_list')) {
                $keys = DB::table('settings')->pluck('key');

                return $this->successResponse('All Keys fetched successfully', $keys);
            }
            // get pagination number
            $perPage = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

            // data by keys
            if ($request->has('keys')) {
                $data = DB::table('settings')->whereIn('key', $request->input('keys'))->get();
                return $this->successResponse('Data fetched successfully.', $data);
            }
            // default response
            return $this->successResponse('Data fetched successfully', Setting::paginate($perPage));

        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
    /**
     * Update of create settings
    */
    public function setSetting(PlatformSettingUpdateOrCreateRequest $request): JsonResponse
    {
        try {
            $requestData = $request->all();

            foreach ($requestData as $key => $value) {
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $request->path;
                    $fileData = $this->uploads($file, $path);
                    $fileUrl = $fileData['fileName'];
                    $response = Setting::updateOrCreate(['key' => $key],[
                        'key' => $key,
                        'value' => $fileUrl
                    ]);

                } else {
                    $response = Setting::updateOrCreate(['key' => $key],[
                        'key' => $key,
                        'value' => $value
                    ]);
                }
            }
            return $this->successResponse("Data create or updated successfully", DB::table('settings')->latest()->get());
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}
