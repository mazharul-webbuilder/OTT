@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Root Category Edit</h4>
                            @include('admin.includes.errors')
                            <form method="POST" action="{{ route('root-category.update', $data->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Title</label>
                                    <input type="text" class="form-control slug-input" id="formrow-firstname-input-title"
                                        placeholder="Enter Your First Name" name="title" value="{{ $data->title }}">
                                </div>
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Slug</label>
                                    <input type="text" class="form-control slug-output" id="formrow-firstname-input-slug"
                                        placeholder="Enter Your First Name" name="slug" value="{{ $data->slug }}">
                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Order</label>
                                            <input type="number" class="form-control" id="formrow-root-category-order"
                                                name="order" value="{{ $data->order }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Status</label> 
                                            <select class="form-select" name="status">
                                                    <option selected value="">Select Status</option>
                                                    @foreach (config('constants.CATEGORYSTATUS') as $item)
                                                         <option @if ($item == $data->status)
                                                            selected
                                                        @endif value="{{$item}}">{{$item}}</option>
                                                    @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                     
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Image</label>
                                            <input type="file" name="file" class="form-control"
                                                id="formrow-root-category-order">
                                            <img height="100px" width="150px" src="{{ url('' . $data->image) }}"
                                                alt="{{ $data->title }}">
                                        </div>
                                    </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="formrow-email-input" class="form-label"></label>  
                                        <div class="form-check"> 
                                            <label for="formrow-email-input" class="form-label"></label>
                                            <input class="form-check-input" type="checkbox" id="is_fixed" name="is_fixed"
                                                @if ($data->is_fixed == 1) checked @endif>
                                            <label class="form-check-label" for="gridCheck">
                                                Fixed Root Category
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                </div>

                               


                                <div>
                                    <button type="submit" class="btn btn-primary w-md">Update</button>
                                </div>
                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->

                <!-- end col -->

            </div>
        </div>
        <!-- container-fluid -->
    </div>
@endsection
