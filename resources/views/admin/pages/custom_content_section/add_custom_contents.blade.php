@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Add Custom Contents</h4>

                            <form method="POST" action="{{ route('add_custom_content_section_content') }}"
                                enctype="multipart/form-data">
                                @csrf
                                {{-- @method('PUT') --}}

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Content Type Title</label>
                                    <input type="text" class="form-control slug-input" id="formrow-firstname-input-title"
                                        value="{{ $data->content_type_title }}" name="content_type_title" readonly>
                                    <input type="hidden" class="form-control slug-input" id="formrow-firstname-input-title"
                                        value="{{ $data->id }}" name="content_type_id" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">More Info Slug</label>
                                    <input type="text" class="form-control slug-output" id="formrow-firstname-input-slug"
                                        value="{{ $data->more_info_slug }}" name="more_info_slug" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">

                                        <div class="mb-3">
                                            <label>Root Category</label>
                                            <select class="form-select" name="root_category_id" id="root_category_id">
                                                <option selected>Select Root Category</option>
                                                @foreach ($root_categories as $item)
                                                    <option class="form-opt" value="{{ $item->id }}"
                                                        onchange="getSubCategory($item->id)">
                                                        {{ $item->title }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label>SubCategory</label>
                                            <select class="form-select" name="sub_category_id" id="sub_category_id">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label>Sub Sub Category</label>
                                            <select class="form-select" name="sub_sub_category_id" id="sub_sub_category_id">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">

                                    <label class="form-label">Select Contents</label>
                                    <select class="select2 form-select select2-multiple" multiple="multiple"
                                        data-placeholder="Choose ..." id="content" name="content_id[]"> 
                                        {{-- <option value="AK">Alaska</option>
                                        <option value="HI">Hawaii</option> --}} 
                                    </select>  
                                </div>  
                                <div>
                                    <button type="submit" class="btn btn-primary w-md">Add Contents</button>
                                </div> 
                            </form> 

                        </div>
                        <!-- end card body -->
                        <div class="card-body"> 
                            <h4 class="card-title">Custom Contents</h4>
                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">

                                <thead>
                                    <tr>

                                        <th>Poster</th>
                                        <th>Content Title</th>
                                        <th>Active</th>
                                        <th>Publish Date</th>
                                        <th>Sorting Position</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($custom_contents as $item)
                                        <tr>
                                            <input type="hidden" class="serdelet_val" value={{ $item->id }}>
                                            <td><img height="50px" width="100px"
                                                    src="{{ url('' . $item->ottContent->poster) }}"
                                                    alt="{{ $item->ottContent->title }}"></td>
                                            <td>{{ $item->ottContent->title }}</td>
                                            <td>
                                                @if ($item->is_active == false)
                                                    <span class="badge badge-pill badge-soft-danger font-size-12">No</span>
                                                @else
                                                    <span
                                                        class="badge badge-pill badge-soft-primary font-size-12">Yes</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->publish_date }}</td>
                                            <td>{{ $item->sorting_position }}</td>
                                            <td> 
                                                <a title="Edit Content" class="btn btn-warning mdi mdi-book-edit-outline"
                                                    href="{{ route('edit_custom_content_section_content', $item->id) }}"></a>
                                                <a class="btn btn-danger delete mdi mdi-delete" href="javascript:void(0)"></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).on('change', '#root_category_id', function() {
        var root_category_id = this.value;
        $.ajax({
            url: '/admin/filter-sub_category-with-content/' + root_category_id,
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
                $('#content').empty();
                $('#content').append('<option value="">' + "Select" +
                    '</option>');
                $.each(response.contents, function(index, content) {
                    $('#content').append('<option value="' +
                        content.id + '">' + content.title +
                        '</option>');
                })
            }
        });
    });
    $(document).on('change', '#sub_category_id', function() {
        var root_category_id = this.value;
        $.ajax({
            url: '/admin/filter-sub_sub_category-with-content/' + root_category_id,
            data: {
                root_category_id
            },
            type: "GET",
            success: function(response) {
                //location.reload();
                // console.log(response.districts[0].id);
                $('#sub_sub_category_id').empty();
                $('#sub_sub_category_id').append('<option value="">' + "Select" +
                    '</option>');
                $.each(response.sub_sub_categories, function(index, sub_sub_category) {
                    $('#sub_sub_category_id').append('<option value="' +
                        sub_sub_category.id + '">' + sub_sub_category.title +
                        '</option>');
                })
                $('#content').empty();
                $('#content').append('<option value="">' + "Select" +
                    '</option>');
                $.each(response.contents, function(index, content) {
                    $('#content').append('<option value="' +
                        content.id + '">' + content.title +
                        '</option>');
                })
            }
        });
    });
    $(document).on('change', '#sub_sub_category_id', function() {
        var root_category_id = this.value;
        $.ajax({
            url: '/admin/filter-sub_sub_category-content/' + root_category_id,
            data: {
                root_category_id
            },
            type: "GET",
            success: function(response) {
                //location.reload();
                // console.log(response.districts[0].id);
                $('#content').empty();
                $('#content').append('<option value="">' + "Select" +
                    '</option>');
                $.each(response.contents, function(index, content) {
                    $('#content').append('<option value="' +
                        content.id + '">' + content.title +
                        '</option>');
                })
            }
        });
    });

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
                                url: '/admin/frontend-custom-content-section-content-delete/' + data,
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
