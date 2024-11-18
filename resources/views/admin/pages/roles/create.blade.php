@extends('admin.layout')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-4">Create Role</h4>
                            @include('admin.includes.errors')
                            <form method="POST" action="{{ route('roles.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-firstname-input" class="form-label">Name</label>
                                            <input type="text" class="form-control slug-input"
                                                id="formrow-firstname-input-title" name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6">
                                        <div class="mt-4">
                                            <h5 class="font-size-14 mb-4"><i
                                                    class="mdi mdi-arrow-right text-primary me-1"></i> Permissions :
                                            </h5>
                                            @foreach ($permission as $value)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="permission{{ $value->id }}" name="permission[]"
                                                        value="{{ $value->id }}">
                                                    <label class="form-check-label" for="permission{{ $value->id }}">
                                                        {{ $value->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                                </div> 

                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>



        </div>
        <!-- container-fluid -->
    </div>
    
@endsection
