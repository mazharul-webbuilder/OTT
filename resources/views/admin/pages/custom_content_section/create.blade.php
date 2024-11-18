@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Custom Section Create</h4>
                            @include('admin.includes.errors')

                            <form method="POST" action="{{ route('frontend-custom-content-section.store') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Content Type Title</label>
                                    <input type="text" class="form-control slug-input" id="formrow-firstname-input-title"
                                        value="{{ old('content_type_title') }}" name="content_type_title">
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">More Info Slug</label>
                                    <input type="text" class="form-control slug-output" id="formrow-firstname-input-slug"
                                        name="more_info_slug">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Content Type Slug</label>
                                            <input type="number" class="form-control" id="formrow-root-category-order"
                                                name="content_type_slug">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_available_on_single_page"
                                                    name="is_available_on_single_page">
                                                <label class="form-check-label" for="is_available_on_single_page">
                                                    Is Available On Single Page
                                                </label>
                                            </div>
                                        </div>
        
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_featured_section"
                                                    name="is_featured_section">
                                                <label class="form-check-label" for="is_featured_section">
                                                    Is Featured Section
                                                </label>
                                            </div>
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

                                        <h4 class="card-title">Custom Section</h4>
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Content Type Title</th>
                                                    <th>Content Type Slug</th>
                                                    <th>Is Available On Single Page</th>
                                                    <th>Is Featured Section</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($custom_sections as $item)
                                                    <tr>
                                                        <input type="hidden" class="serdelet_val"
                                                            value={{ $item->id }}>
                                                        <td>{{ $item->content_type_title }}</td>
                                                        <td>{{ $item->content_type_slug }}</td>
                                                        <td>
                                                            @if ($item->is_available_on_single_page == false)
                                                                <span
                                                                    class="badge badge-pill badge-soft-danger font-size-12">No</span>
                                                            @else
                                                                <span
                                                                    class="badge badge-pill badge-soft-primary font-size-12">Yes</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->is_featured_section == false)
                                                                <span
                                                                    class="badge badge-pill badge-soft-danger font-size-12">No</span>
                                                            @else
                                                                <span
                                                                    class="badge badge-pill badge-soft-primary font-size-12">Yes</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a title="Add Contents"
                                                                class="btn btn-success mdi mdi-file-plus"
                                                                href="{{ route('frontend-custom-content-section.addContent', $item->id) }}"></a>
                                                            <a class="btn btn-warning mdi mdi-book-edit-outline"
                                                                href="{{ route('frontend-custom-content-section.show', $item->id) }}"></a>
                                                            <a  class="btn btn-danger delete mdi mdi-delete"
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
                            url: '/admin/frontend-custom-content-section-delete/' + data,
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
            })
        });
    </script>
@endsection
