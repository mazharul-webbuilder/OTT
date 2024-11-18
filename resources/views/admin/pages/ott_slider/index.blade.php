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

                                        <h4 class="card-title">Slider</h4>
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Image</th>
                                                    <th>Root Category</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th>Order</th>
                                                    <th>Bottom Title</th>
                                                    <th>Content URL</th>
                                                    <th>Is Home</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($ott_sliders as $item)
                                                    <tr>
                                                        <input type="hidden" class="serdelet_val"
                                                            value={{ $item->id }}>
                                                        <td>{{ $item->title }}</td>
                                                        <td><img height="50px" width="100px" src="{{ $item->image }}"
                                                                alt="{{ $item->title }}"></td>
                                                        <td>{{ $item->rootCategory->title }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td>{{ $item->status }}</td>
                                                        <td>{{ $item->order }}</td>
                                                        <td>{{ $item->bottom_title }}</td>
                                                        <td>{{ $item->content_url }}</td>
                                                        <td>{{ $item->is_home ? 'Yes' : 'No' }}</td>
                                                        <td>
                                                            <a class="btn btn-warning mdi mdi-book-edit-outline"
                                                                href="{{ route('ott-slider.show', $item->id) }}"></a>
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
                // alert('hello');
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
                            url: '/admin/ott-slider-delete/' + data,
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
