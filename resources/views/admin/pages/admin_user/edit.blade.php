@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Admin Edit</h4>
                            @include('admin.includes.errors')
                            <form method="POST" action="{{ route('admin.update', $admin->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title mb-4">Admin User Edit</h4> 
                                            
                                                <div class="col-md-6">

                                                    <div class="mb-3">
                                                        <label for="useremail" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="useremail" name="email"
                                                            placeholder="Enter email" required value="{{ $admin->email }}">
                                                        <div class="invalid-feedback">
                                                            Please Enter Email
                                                        </div>
                                                    </div>
                
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="name" name="name"
                                                            placeholder="Enter username" required value="{{ $admin->name }}">
                                                        <div class="invalid-feedback">
                                                            Please Enter Username
                                                        </div>
                                                    </div>
                                                </div>
                
                                                <div class="col-md-6">

                                                    <div class="mb-3">
                                                        <label for="userpassword" class="form-label">Password</label>
                                                        <input type="password" class="form-control" id="userpassword"
                                                            placeholder="Enter password" name="password">
                                                        <div class="invalid-feedback">
                                                            Please Enter Password
                                                        </div>
                                                    </div>
 
                                                    <div class="mb-3">
                                                        <label for="userpassword" class="form-label">Password</label>
                                                        <input type="password" class="form-control" id="userpassword"
                                                            placeholder="Enter password" name="password_confirmation">
                                                        <div class="invalid-feedback">
                                                            Please Confirm Password
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label>Roles</label>
                                                        <select class="form-select" name="role_id">
                                                            <option selected value="">Select Role</option>
                                                            @foreach ($roles as $item)
                                                                <option {{ $admin->hasRole($item->name) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                
                                                <div class="mb-3">
                
                                                    <div class="form-check">
                                                        <input @if ($admin->is_active == 1)
                                                           checked 
                                                        @endif class="form-check-input" type="checkbox" id="is_fixed" name="is_active">
                                                        <label class="form-check-label" for="gridCheck">
                                                            Is Active
                                                        </label>
                                                    </div>
                                                </div> 
                                           
                                        </div>
                                        <!-- end card body -->
                                    </div>
                                    <!-- end card -->
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
