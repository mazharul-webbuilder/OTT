@extends('admin.layout')
@section('content')
    
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="card-title">Role Management</h4>
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Name</th> 
                                                    <th>Action</th>
                                                </tr>
                                            </thead> 

                                            <tbody>
                                                @foreach ($roles as $key => $role)
                                                    <tr>
                                                        <input type="hidden" class="serdelet_val"
                                                            value={{ $role->id }}>
                                                         <td>{{$loop->index+1}}</td>
                                                         <td>{{$role->name}}</td>
                                                        <td>
                                                            <a title="Edit" class="btn btn-warning mdi mdi-book-edit-outline"
                                                                href="{{ route('roles.edit', $role->id) }}"></a>
                                                            <a title="View" class="btn btn-info mdi mdi-eye-circle-outline"
                                                                href="{{ route('roles.show', $role->id) }}"></a>
                                                            <a id="delete_role" title="Delete" class="btn btn-danger delete mdi mdi-delete"
                                                                href="#"></a>
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
   
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 
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
                        url: '/admin/role-delete/' + data, 
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
        });
 
    });

</script>
@endsection