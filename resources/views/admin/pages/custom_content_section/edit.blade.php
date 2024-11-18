@extends('admin.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Custom Content Edit</h4>

                            <form method="POST" action="{{ route('frontend-custom-content-section.update', $data->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT') 

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">Content Type Title</label>
                                    <input type="text" class="form-control slug-input" id="formrow-firstname-input-title" value="{{ $data->content_type_title }}" name="content_type_title">
                                </div>

                                <div class="mb-3">
                                    <label for="formrow-firstname-input" class="form-label">More Info Slug</label>
                                    <input type="text" class="form-control slug-output" id="formrow-firstname-input-slug" value="{{ $data->more_info_slug }}" name="more_info_slug">
                                </div>

                                <div class="row"> 
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="formrow-email-input" class="form-label">Content Type Slug</label>
                                            <input type="number" class="form-control" id="formrow-root-category-order"
                                                name="content_type_slug" value="{{ $data->content_type_slug }}">
                                        </div>
                                    </div> 
                                </div>
                                 
                                <div class="mb-3"> 
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_available_on_single_page" name="is_available_on_single_page" @if ($data->is_available_on_single_page == 1)
                                            checked
                                        @endif>
                                        <label class="form-check-label" for="is_available_on_single_page">
                                           Is Available On Single Page
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3"> 
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured_section" name="is_featured_section" @if ($data->is_featured_section == 1)
                                            checked
                                        @endif>
                                        <label class="form-check-label" for="is_featured_section">
                                           Is Featured Section
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
@endsection
