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
                            <form id="active_form" method="POST" action="{{ route('ott-content.store') }}"
                                enctype="multipart/form-data">
                                @csrf

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
                                                            id="basicpill-firstname-input" name="title">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Short Title</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-lastname-input" name="short_title">
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
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label>Sub Sub Category</label>
                                                        <select class="form-select" name="sub_sub_category_id"
                                                            id="sub_sub_category_id">
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
                                                            <option selected>Select Type</option>
                                                            <option value="1">Single</option>
                                                            <option value="2">Series</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label>Series</label>
                                                        <select class="form-select" name="series_id" id="series_id">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Series Order</label>
                                                        <input type="number" class="form-control"
                                                            id="basicpill-lastname-input" name="series_order"
                                                            value="0">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Order</label>
                                                        <input type="number" class="form-control"
                                                            id="basicpill-lastname-input" name="order" value="0">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="basicpill-address-input">Description</label>
                                                        <textarea id="basicpill-address-input" class="form-control" rows="5" name="description"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-lg-2">
                                                    <div class="mb-2">
                                                        <label for="basicpill-lastname-input">Release Date</label>
                                                        <input type="date" class="form-control"
                                                            id="basicpill-lastname-input" name="release_date">
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Runtime</label>
                                                        <input type="number" class="form-control"
                                                            id="basicpill-lastname-input" name="runtime">
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Cloud URL</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-lastname-input" name="cloud_url">
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Youtube URL</label>
                                                        <input type="text" class="form-control"
                                                            id="basicpill-lastname-input" name="youtube_url">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Status</label>
                                                        <select class="form-select" name="status">
                                                            <option selected>Select Status</option>
                                                            <option value="Published">Published</option>
                                                            <option value="Pending">Pending</option>
                                                            <option value="Hold">Hold</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Access</label>
                                                        <select class="form-select" name="access">
                                                            <option selected>Select Access</option>
                                                            <option value="Premium">Premium</option>
                                                            <option value="Free">Free</option>
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
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-lastname-input">Backdrop</label>
                                                        <input type="file" class="form-control"
                                                            id="basicpill-lastname-input" name="backdrop_image">
                                                    </div>
                                                </div>

                                            </div>

                                        </form>
                                    </section>

                                    <!-- Content Meta -->
                                    <h3>Content Meta</h3>
                                    <section>
                                        {{-- <form> --}}
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="basicpill-pancard-input">Key</label>
                                                    <input type="text" class="form-control"
                                                        id="basicpill-pancard-input" placeholder="Enter Key."
                                                        name="key[]">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="basicpill-vatno-input">Value</label>
                                                    <input type="text" class="form-control" id="basicpill-vatno-input"
                                                        placeholder="Enter Key Value." name="value[]">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="basicpill-pancard-input">Key</label>
                                                    <input type="text" class="form-control"
                                                        id="basicpill-pancard-input" placeholder="Enter Key."
                                                        name="key[]">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="basicpill-vatno-input">Value</label>
                                                    <input type="text" class="form-control" id="basicpill-vatno-input"
                                                        placeholder="Enter Key Value." name="value[]">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="basicpill-pancard-input">Key</label>
                                                    <input type="text" class="form-control"
                                                        id="basicpill-pancard-input" placeholder="Enter Key."
                                                        name="key[]">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="basicpill-vatno-input">Value</label>
                                                    <input type="text" class="form-control" id="basicpill-vatno-input"
                                                        placeholder="Enter Key Value." name="value[]">
                                                </div>
                                            </div>

                                        </div>


                                    </section>
                                    {{-- </form> --}}
                                    {{-- <!-- Media Details -->
                                    <h3>Media Details</h3>
                                    <section>
                                        <div>
                                            <form>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="basicpill-namecard-input">Name on Card</label>
                                                            <input type="text" class="form-control"
                                                                id="basicpill-namecard-input"
                                                                placeholder="Enter Your Name on Card">
                                                        </div>
                                                    </div>
    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label>Credit Card Type</label>
                                                            <select class="form-select">
                                                                <option selected>Select Card Type</option>
                                                                <option value="AE">American Express</option>
                                                                <option value="VI">Visa</option>
                                                                <option value="MC">MasterCard</option>
                                                                <option value="DI">Discover</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="basicpill-cardno-input">Credit Card Number</label>
                                                            <input type="text" class="form-control"
                                                                id="basicpill-cardno-input" placeholder="Credit Card Number">
                                                        </div>
                                                    </div>
    
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="basicpill-card-verification-input">Card Verification
                                                                Number</label>
                                                            <input type="text" class="form-control"
                                                                id="basicpill-card-verification-input"
                                                                placeholder="Credit Verification Number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="basicpill-expiration-input">Expiration Date</label>
                                                            <input type="text" class="form-control"
                                                                id="basicpill-expiration-input"
                                                                placeholder="Card Expiration Date">
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </form>
                                        </div>
                                    </section>
    
                                    <!-- Confirm Details -->
                                    <h3>Confirm Detail</h3>
                                    <section>
                                        <div class="row justify-content-center">
                                            <div class="col-lg-6">
                                                <div class="text-center">
                                                    <div class="mb-4">
                                                        <i class="mdi mdi-check-circle-outline text-success display-4"></i>
                                                    </div>
                                                    <div>
                                                        <h5>Confirm Detail</h5>
                                                        <p class="text-muted">If several languages coalesce, the grammar of the
                                                            resulting</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section> --}}
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
    </script>
@endsection
