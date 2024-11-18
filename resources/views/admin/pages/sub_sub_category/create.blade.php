@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Sub Sub Category Create</h4>
                            @include('admin.includes.errors')
                            <form method="POST" action="{{ route('sub-sub-category.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Title</label>
                                    <input type="text" class="form-control slug-input" id="formrow-firstname-input-title"
                                        value="{{ old('title') }}" name="title">
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Slug</label>
                                    <input type="text" class="form-control slug-output" id="formrow-firstname-input-slug"
                                        name="slug">
                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Order</label>
                                            <input type="number" class="form-control" id="formrow-root-category-order"
                                                name="order">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Image</label>
                                            <input type="file" name="file" class="form-control"
                                                id="formrow-root-category-order">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Root Category</label>
                                            <select class="form-select" name="root_category_id" id="root_category_id">
                                                <option selected value="">Select Root Category</option>
                                                @foreach ($root_categories as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Sub Category</label>
                                            <select class="form-select" name="sub_category_id" id="sub_category_id">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select class="form-select" name="status">
                                                <option selected value="">Select Status</option>
                                                @foreach (config('constants.CATEGORYSTATUS') as $item)
                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="formrow-firstname-input" class="form-label">Seo Title</label>
                                            <input type="text" class="form-control slug-input"
                                                value="{{ old('seo_title') }}" name="seo_title">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="formrow-firstname-input" class="form-label">Seo Description</label>
                                            <textarea id="basicpill-address-input" class="form-control" rows="5" name="seo_description"></textarea>
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
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="card-title">Sub Sub Category</h4>
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Image</th>
                                                    <th>Root Category</th>
                                                    <th>Sub Category</th>
                                                    <th>Status</th>
                                                    <th>Order</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($sub_sub_categories as $item)
                                                    <tr>
                                                        <input type="hidden" class="serdelet_val"
                                                            value={{ $item->id }}>
                                                        <td>{{ $item->title }}</td>
                                                        <td><img height="50px" width="100px"
                                                                src="{{ url('' . $item->image) }}"
                                                                alt="{{ $item->title }}"></td>
                                                        <td>{{ $item->rootCategory->title }}</td>
                                                        <td>{{ $item->subCategory->title }}</td>
                                                        <td>{{ $item->status }}</td>
                                                        <td>{{ $item->order }}</td>
                                                        <td>
                                                            <a class="btn btn-warning mdi mdi-book-edit-outline"
                                                                href="{{ route('sub-sub-category.show', $item->id) }}"></a>
                                                            <a class="btn btn-danger delete mdi mdi-delete"
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
                            url: '/admin/sub-sub-category-delete/' + data,
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


            $('#root_category_id').change(function() {
                var root_category_id = this.value;
                $.ajax({
                    url: '/admin/filter-sub_category/' + root_category_id,
                    data: {
                        root_category_id
                    },
                    type: "GET",
                    success: function(response) {
                        //location.reload();
                        // console.log(response.districts[0].id);
                        $('#sub_category_id').empty();
                        $('#sub_category_id').append('<option value="">' + "Select" +
                            '</option>');
                        $.each(response.sub_categories, function(index, sub_category) {
                            $('#sub_category_id').append('<option value="' +
                                sub_category.id + '">' + sub_category.title +
                                '</option>');
                        })
                    }
                });
            });
        });
    </script>
@endsection
