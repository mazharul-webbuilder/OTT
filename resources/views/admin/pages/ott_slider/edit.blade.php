@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid"> 
            <div class="row justify-content-center">
                <div class="col-xl-10 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Ott Slider Edit</h4>
                            @include('admin.includes.errors')   
                            <form method="POST" action="{{ route('ott-slider.update', $data->id) }}"
                                enctype="multipart/form-data">
                                @csrf 
                                @method('PUT') 
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Title</label>
                                    <input type="text" class="form-control slug-input" id="formrow-firstname-input-title" value="{{$data->title}}" name="title">
                                </div>
                
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Slug</label>
                                    <input type="text" class="form-control slug-output" id="formrow-firstname-input-slug" name="slug" value="{{ $data->slug }}">
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
                                            <label for="formrow-email-input" class="form-label">Order</label>
                                            <input type="number" class="form-control" id="formrow-root-category-order"
                                                name="order" value="{{$data->order}}">
                                        </div>
                                    </div>
                
                                </div>
                
                                <div class="row">
                
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Image</label>
                                            <input type="file" name="file" class="form-control"
                                                id="formrow-root-category-order">
                                            <img height="100px" width="150px" src="{{ url('' . $data->image) }}"
                                                alt="{{ $data->title }}">
                                        </div>
                                    </div>
                                </div>
                
                                <div class="row">  
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="formrow-firstname-input" class="form-label">Bottom Title</label>
                                            <input type="text" class="form-control slug-input"  value="{{$data->bottom_title}}" name="bottom_title">
                                        </div>
                                    </div>
                
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="formrow-firstname-input" class="form-label">Description</label>
                                             <textarea id="basicpill-address-input" class="form-control" rows="5" name="description">{{$data->description}}</textarea>
                                        </div>
                                    </div> 
                                </div>
                              
                
                                <div class="row"> 
                
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-firstname-input" class="form-label">Content URL</label>
                                            <input type="text" class="form-control slug-input"  value="{{  $data->content_url }}" name="content_url">
                                        </div>
                                    </div>
                
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label>Status</label> 
                                            <select class="form-select" name="status">
                                                    <option selected value="">Select Status</option>
                                                    @foreach (config('constants.CATEGORYSTATUS') as $item)
                                                         <option @if ($data->status == $item)
                                                             selected
                                                         @endif value="{{$item}}">{{$item}}</option>
                                                    @endforeach 
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                
                                <div class="mb-3"> 
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_home" name="is_home"
                                            @if ($data->is_home == 1) checked @endif>
                                        <label class="form-check-label" for="gridCheck">
                                            Is Home
                                        </label>
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
        });

    </script>
@endsection
