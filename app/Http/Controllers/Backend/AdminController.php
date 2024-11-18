<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\AdminResource;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;
use Closure;

class AdminController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
         $this->middleware('permission:admin-user-list|admin-user-create|admin-user-edit|admin-user-delete', ['only' => ['index', 'show']]);
         $this->middleware('permission:admin-user-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:admin-user-edit', ['only' => ['show', 'update']]);
         $this->middleware('permission:admin-user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Admin::with(['roles'])->latest();

            if ($request->filled('query_string')) {
                $query = getSearchQuery($query, $request->input('query_string'), 'name', 'email');
            }
            $admins = $query->paginate(10);

            return $this->successResponse('Data fetched Successfully', $admins);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $rules = array(
            'name' => 'required|string',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
            'role_id' => ['required', Rule::exists('roles', 'id'),
                function(string $attribute, mixed $value, Closure $fail){
                    if (DB::table('roles')->where('id', $value)->value('name') == 'Super Admin') {
                        $fail("Can't use this role id");
                    }
                }
            ]
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null);
        }

        try {
            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $user->assignRole([$request->role_id]);
            return $this->successResponse('Admin Added successfully', $user);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = new AdminResource(Admin::find($id));
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request,int $id): JsonResponse
    {
        try {
            // Get Admin
            $admin = Admin::find($id);

            // Check admin found or not
            if (is_null($admin)) {
                return $this->errorResponse(message: "Data not found", data: null);
            }

            // Validation Data
            $request->validate([
                'name' => 'required|string|max:50',
                'email' =>  'required|email|unique:admins,email,' . $admin->id, // unique email except this $admin->id
                'password' => 'nullable|min:6|confirmed',
                'role_id' => ['required', Rule::exists('roles', 'id'),
                    function(string $attribute, mixed $value, Closure $fail){
                        if (DB::table('roles')->find($value)->name == 'Super Admin') {
                            $fail("Can't use this role id");
                        }
                    }
                ],
            ]);

            $admin->name = $request->name;
            $admin->email = $request->email;

            if (isset($request->password)) {
                $admin->password = Hash::make($request->password);
            }
            $admin->update();

            $admin->roles()->detach();
            if ($request->role_id) {
                $admin->assignRole([$request->role_id]);
            }

            return $this->successResponse('Data updated Successfully', $admin);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $admin = Admin::findOrFail($id);

            if ($admin->delete()) {
                return $this->successResponse('Data Deleted Successfully', null);
            } else {
                return $this->errorResponse(message: 'You can\'t delete super admin', data: null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}
