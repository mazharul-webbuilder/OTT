@extends('admin.layout')

@section('content')
    <div class="page-content">

        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Upload Content</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @include('admin.includes.errors')
                            <h4 class="card-title mb-4">Content Upload</h4>
                            <form id="active_form" method="POST" action="{{ route('ott-content.update', $data->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div id="basic-example">
                                    <!-- Content Details -->
                                    <h3>General</h3>

                                    <section>
                                        <form>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-firstname-input">Title</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-firstname-input" name="title"
                                                            value="{{ $data->title }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Short Title</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-lastname-input" name="short_title"
                                                            value="{{ $data->short_title }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">

                                                    <div class="mb-3">
                                                        <label>Root Category</label>
                                                        <select class="form-select" name="root_category_id"
                                                            id="root_category_id">
                                                            <option selected>Select Root Category</option>
                                                            @foreach ($root_categories as $item)
                                                                <option class="form-opt" value="{{ $item->id }}"
                                                                    @if ($data->root_category_id == $item->id) selected @endif
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
                                                        <select class="form-select" name="sub_category_id"
                                                            id="sub_category_id">
                                                            <option class="form-opt" value="{{ $data->sub_category_id }}">
                                                                {{ $data->subCategory->title }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
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
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label>Content Type</label>
                                                        <select class="form-select" name="content_type_id"
                                                            id="content_type_id">

                                                            <option>Select Type</option>
                                                            <option value="1"
                                                                @if ($data->content_type_id == 1) selected @endif>Single
                                                            </option>
                                                            <option value="2"
                                                                @if ($data->content_type_id == 2) selected @endif>Series
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label>Series</label>
                                                        <select class="form-select" name="series_id" id="series_id">
                                                            <option class="form-opt" value="{{ $data->series_id }}">
                                                                {{ $data->ottSeries->title }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Series Order</label>
                                                        <input type="number" class="form-control"
                                                            id="basicpill-lastname-input" name="series_order"
                                                            value="{{ $data->series_order }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Order</label>
                                                        <input type="number" class="form-control"
                                                            id="basicpill-lastname-input" name="order"
                                                            value="{{ $data->order }}">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="basicpill-address-input">Description</label>
                                                        <textarea id="basicpill-address-input" class="form-control" rows="5" name="description">{{ $data->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-lg-2">
                                                    <div class="mb-2">
                                                        <label for="basicpill-lastname-input">Release Date</label>
                                                        <input type="date" class="form-control"
                                                            id="basicpill-lastname-input" name="release_date"
                                                            value="{{ $data->release_date }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Runtime</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-lastname-input" name="runtime"
                                                            value="{{ $data->runtime }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Cloud URL</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-lastname-input" name="cloud_url"
                                                            value="{{ $data->cloud_url }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Youtube URL</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-lastname-input" name="youtube_url"
                                                            value="{{ $data->youtube_url }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Status</label>
                                                        <select class="form-select" name="status">
                                                            <option selected>Select Status</option>
                                                            <option value="Published"
                                                                @if ($data->status == 'Published') selected @endif>Published
                                                            </option>
                                                            <option value="Pending"
                                                                @if ($data->status == 'Pending') selected @endif>Pending
                                                            </option>
                                                            <option value="Hold"
                                                                @if ($data->status == 'Hold') selected @endif>Hold
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Access</label>
                                                        <select class="form-select" name="access">
                                                            <option selected>Select Access</option>
                                                            <option value="Premium"
                                                                @if ($data->access == 'Premium') selected @endif>Premium
                                                            </option>
                                                            <option value="Free"
                                                                @if ($data->access == 'Free') selected @endif>Free
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Poster</label>
                                                        <input type="file" class="form-control"
                                                            id="basicpill-lastname-input" name="poster_image">
                                                        <img height="100px" width="150px" src="{{ $data->poster }}"
                                                            alt="{{ $data->title }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Backdrop</label>
                                                        <input type="file" class="form-control"
                                                            id="basicpill-lastname-input" name="backdrop_image">
                                                        <img height="100px" width="150px" src="{{ $data->backdrop }}"
                                                            alt="{{ $data->title }}">
                                                    </div>
                                                </div>

                                            </div>

                                        </form>
                                    </section>

                                    <!-- Content Meta -->
                                    <h3>Content Meta</h3>
                                    <section>
                                        {{-- <form> --}}
                                        @foreach ($data->ottContentMeta as $item)
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-pancard-input">Key</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-pancard-input" placeholder="Enter Key."
                                                            name="key[]" value="{{ $item->key }}">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-vatno-input">Value</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-vatno-input" placeholder="Enter Key Value."
                                                            name="value[]" value="{{ $item->value }}">
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach



                                    </section>
                                    {{-- </form> --}}
                                    {{-- <!-- Media Details --> --}}
                                    <h3>Media Details</h3>
                                    <section>
                                        <div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label for="basicpill-vatno-input">Content
                                                                        Trailer</label>
                                                                    <a href="{{ route('upload.Trailer', $data->id) }}"
                                                                        class="button button-class">Upload Trailer</a>
                                                                </div>

                                                            </div>
                                                            <style>
                                                                .progress {
                                                                    display: none;
                                                                }
                                                            </style>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            @foreach ($data->contentTrailer as $item)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <video height="150" width="400" controls
                                                                    poster="{{ $data->poster }}">
                                                                    {{-- <source
                                                                        src="{{ asset('storage/videos/' . $item->content_source) }}"
                                                                        type="video/mp4"> --}}
                                                                    {{-- <source src="movie.ogg" type="video/ogg"> --}}
                                                                    <source src="{{ $item->trailer }}" type="video/mp4">

                                                                    {{-- <source src="movie.ogg" type="video/ogg"> --}}
                                                                    {{-- Your browser does not support the video tag. --}}
                                                                </video>
                                                                {{-- <a href="{{ route('delete-media', $item->id) }}">Delete
                                                                    This
                                                                    File</a> --}}
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach


                                        </div>
                                        <div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label for="basicpill-vatno-input">Content
                                                                        Video</label>
                                                                    <a href="{{ route('upload.media', $data->id) }}"
                                                                        class="button button-class">Upload Media</a>
                                                                </div>

                                                            </div>
                                                            <style>
                                                                .progress {
                                                                    display: none;
                                                                }
                                                            </style>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            @foreach ($data->contentSource as $item)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <label
                                                                    for="basicpill-vatno-input">{{ $item->source_format }}</label>
                                                                <video height="150" width="400" controls
                                                                    poster="{{ $data->poster }}">
                                                                    {{-- <source
                                                                        src="{{ asset('storage/videos/' . $item->content_source) }}"
                                                                        type="video/mp4"> --}}
                                                                    {{-- <source src="movie.ogg" type="video/ogg"> --}}
                                                                    <source src="{{ $item->content_source }}"
                                                                        type="video/mp4">
                                                                    <track
                                                                        src="/OTT/Content/Subtitles/1668343097subtitle.vtt"
                                                                        label="English" kind="captions" srclang="en-us"
                                                                        default>
                                                                    {{-- <source src="movie.ogg" type="video/ogg"> --}}
                                                                    {{-- Your browser does not support the video tag. --}}
                                                                </video>
                                                                <a href="{{ route('delete-media', $item->id) }}">Delete
                                                                    This
                                                                    File</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach


                                        </div>
                                        <div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label for="basicpill-vatno-input">Content
                                                                        Subtitle</label>
                                                                    <a href="{{ route('upload.subtitle', $data->id) }}"
                                                                        class="button button-class">Upload Media
                                                                        Subtitle</a>
                                                                </div>

                                                            </div>
                                                            <style>
                                                                .progress {
                                                                    display: none;
                                                                }
                                                            </style>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>
                                    </section>
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
    {{-- @dd(session('message')) --}}

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).on('change', '#root_category_id', function() {
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
        $(document).on('change', '#content_type_id', function() {
            var content_type_id = this.value;

            if (content_type_id == 2) {

                var root_category_id = $('#root_category_id').val();
                var sub_category_id = $('#sub_category_id').val();
                var sub_sub_category_id = $('#sub_sub_category_id').val();
                // alert(sub_sub_category_id);
                $.ajax({
                    url: '/admin/filter-series',
                    data: {
                        root_category_id: root_category_id,
                        sub_category_id: sub_category_id,
                        sub_sub_category_id: sub_sub_category_id
                    },
                    type: "GET",
                    success: function(response) {
                        $('#series_id').empty();
                        $('#series_id').append('<option value="">' + "Select" +
                            '</option>');
                        $.each(response.serieses, function(index, series) {
                            $('#series_id').append('<option value="' +
                                series.id + '">' + series.title +
                                '</option>');
                        })
                    }
                });

            }


        });
    </script> --}}
@endsection
