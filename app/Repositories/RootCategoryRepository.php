<?php

namespace App\Repositories;

use App\Http\Resources\Backend\RootCategoryResource as BackendRootCategoryResource;
use App\Http\Resources\RootCategoryResource;
use App\Interfaces\CrudInterface;
use App\Models\RootCategory;
use Illuminate\Pagination\Paginator;

class RootCategoryRepository implements CrudInterface{

    public function getAll($per_page)
    {
        return RootCategory::latest()->paginate($per_page);
    }

    public function getCategories(){
        return BackendRootCategoryResource::collection(RootCategory::where('status','Published')->latest()->get());
    }

}
