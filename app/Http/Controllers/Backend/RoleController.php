<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleCreateRequest;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:role-create', ['only' => ['store']]);
        $this->middleware('permission:role-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Role::with('permissions');
            if ($request->filled('query_string'))
            {
                $query = getSearchQuery(query: $query, queryString:  $request->input('query_string'), columns: 'name');
            }

            return $this->successResponse(message: "Data fetched successfully", data: $query->paginate(10));
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleCreateRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => 'admin'
            ]);
            // Set Role permissions
            $permissions = json_decode($request->input('permissions')); // array

            $role->syncPermissions($permissions);
            DB::commit();
            return $this->successResponse(message: 'Role Created successfully', data:  $role);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse(message: $e->getMessage(), data: null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $role = Role::find($id);
            $permissions = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id')
                ->all();
            $data['role'] = $role;
            $data['permissions'] = $permissions;
            $data['rolePermissions'] = $rolePermissions;

            return $this->successResponse('Data fetched successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $this->validate($request, [
                'name' => ['required', Rule::notIn(['Super Admin'])],
                'permissions' => 'required|JSON',
            ]);

            DB::beginTransaction();
            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->update();
            // Sync Permissions
            $role->syncPermissions(json_decode($request->input('permissions')));
            DB::commit();
            return $this->successResponse('Role Updated successfully', $role);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $role = Role::find($id);
            if (!is_null($role)) {
                $role->delete();
            }
            return $this->successResponse('Role Deleted Successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}
