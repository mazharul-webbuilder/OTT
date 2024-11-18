@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Ott Series Edit</h4>

                            <form method="POST" action="{{ route('ott-series.update', $data->id) }}"
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
                                            <label>Root Category</label>
                                            <select class="form-select" name="root_category_id" id="root_category_id">
                                                    <option selected value="">Select Root Category</option> 
                                                    @foreach ($root_categories as $item)
                                                         <option @if ($item->id == $data->root_category_id)
                                                             selected
                                                         @endif value="{{ $item->id }}">{{ $item->title }}</option>
                                                    @endforeach
                                                   
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>SubCategory</label>
                                            <select class="form-select" name="sub_category_id"
                                                id="sub_category_id">
                                                <option class="form-opt" value="{{ $data->sub_category_id }}">
                                                    {{ $data->subCategory->title }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Sub Sub Category</label>
                                            <select class="form-select" name="sub_sub_category_id"
                                                id="sub_sub_category_id">
                                                <option class="form-opt"
                                                    value="{{ $data->sub_sub_category_id }}">
                                                    {{ $data->subSubCategory->title }}
                                                </option>
                                            </select>
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


    <script type="text/javascript">
        $(document).ready(function() {
             
            $('#root_category_id').change(function(){
                var root_category_id = this.value;
                $.ajax({
                    url: '/admin/filter-sub_category/'+root_category_id,
                    data: {root_category_id},
                    type: "GET",
                    success: function(response){
                        //location.reload();
                        // console.log(response.districts[0].id);
                        $('#sub_category_id').empty();
                        $('#sub_category_id').append('<option value="">'+"Select"+'</option>');
                        $.each(response.sub_categories,function(index,sub_category){
                            $('#sub_category_id').append('<option value="'+sub_category.id+'">'+sub_category.title+'</option>');
                        })
                    }
                });
            });


            $(document).on('change', '#sub_category_id', function() {
            var root_category_id = this.value;
            $.ajax({
                url: '/admin/filter-sub_sub_category/' + root_category_id,
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
                }
            });
        });


        });

    </script>
@endsection
