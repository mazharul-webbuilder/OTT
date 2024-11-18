@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Admin User Create</h4>

                            @include('admin.includes.errors')
                            <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="useremail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="useremail" name="email"
                                            placeholder="Enter email" required>
                                        <div class="invalid-feedback">
                                            Please Enter Email
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter username" required>
                                        <div class="invalid-feedback">
                                            Please Enter Username
                                        </div>
                                    </div>
                                </div>




                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="userpassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="userpassword"
                                            placeholder="Enter password" name="password" required>
                                        <div class="invalid-feedback">
                                            Please Enter Password
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Roles</label>
                                        <select class="form-select" name="role_id">
                                            <option selected value="">Select Role</option>
                                            @foreach ($roles as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_fixed" name="is_active">
                                        <label class="form-check-label" for="gridCheck">
                                            Is Active
                                        </label>
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
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="card-title">Admin Users</h4>
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Is Active</th>
                                                    <th>Role</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>


                                            <tbody>
                                                @foreach ($admins as $item)
                                                    <tr>
                                                        <input type="hidden" class="serdelet_val"
                                                            value={{ $item->id }}>
                                                        <td>{{ $item->name }}</td> 
                                                        <td>{{ $item->email }}</td>
                                                        <td>{{ $item->is_active }} 
                                                        @foreach ($item->roles as $role)
                                                        <td>{{ $role->name }}</td>
                                                @endforeach
                                                <td>
                                                    <a class="btn btn-warning"
                                                        href="{{ route('admin.edit', $item->id) }}">Edit</a>
                                                    <a class="btn btn-danger delete" href="#">Delete</a>
                                                </td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div> <!-- end col -->
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
    {{-- @dd(session('message')) --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        var slug = function(str) {
            var $slug = '';
            var trimmed = $.trim(str);
            $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
            replace(/-+/g, '-').
            replace(/^-|-$/g, '');
            return $slug.toLowerCase();
        }
        $('.slug-input').keyup(function() {
            var takedata = $('.slug-input').val()
            // alert(takedata);
            $('.slug-output').val(slug(takedata));
            // var domain = $('.yourdomain').val().toLowerCase();
            // $('.website').text(domain)
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete').click(function(e) {
                e.preventDefault();
                var data = $(this).closest("tr").find(".serdelet_val").val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/admin-delete/' + data,
                            data: {
                                data
                            },
                            success: function(response) {
                                //location.reload();
                                Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                    )
                                    .then((result) => {
                                        location.reload();
                                    })
                            }
                        });
                    }
                })
            })
        });
    </script>
@endsection
