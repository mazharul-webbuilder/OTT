@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Edit Custom Contents</h4>
                            @include('admin.includes.errors')
                            <form method="POST" action="{{ route('update_custom_content_section_content') }}"
                                enctype="multipart/form-data">
                                @csrf
                                {{-- @method('PUT') --}}
                                <div class="row">
                                   <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="formrow-firstname-input" class="form-label">Publish Date</label>
                                        <input type="datetime-local" class="form-control slug-input" id="formrow-firstname-input-title"
                                            value="{{ $data->publish_date }}" name="publish_date" >
                                        <input type="hidden" class="form-control slug-input" id="formrow-firstname-input-title"
                                            value="{{ $data->id }}" name="custom_content_id">
                                    </div>
                                   </div>
                                   <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="formrow-firstname-input" class="form-label">Sorting Position</label>
                                        <input type="text" class="form-control slug-input" id="formrow-firstname-input-title"
                                            value="{{ $data->sorting_position }}" name="sorting_position">
                                         
                                    </div>
                                   </div>
                                   <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input @if ($data->is_active == 1)
                                                checked
                                            @endif class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active">
                                            <label class="form-check-label" for="gridCheck">
                                                Is Active
                                            </label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                  
                                <div>
                                    <button type="submit" class="btn btn-primary w-md">Update Content</button>
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
</script>
