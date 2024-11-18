@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-xl-10 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Ott Content Subtitle</h4>
                            @include('admin.includes.errors')
                            <form method="POST" action="{{ route('ott-content-subtitle.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                {{-- @dd($content_id) --}}
                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Title</label>
                                    <input type="hidden" class="form-control slug-input" id="formrow-firstname-input-title"
                                        name="content_id" value="{{ $content_id }}">
                                    <input type="text" class="form-control slug-input" id="formrow-firstname-input-title"
                                        value="{{ old('title') }}" name="title">
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Language</label>
                                    <input type="text" class="form-control slug-input" id="formrow-firstname-input-title"
                                        value="{{ old('title') }}" name="language">
                                </div>


                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">File</label>
                                            <input type="file" name="file" class="form-control"
                                                id="formrow-root-category-order">
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
