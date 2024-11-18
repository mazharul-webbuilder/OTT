@extends('admin.layout')

@section('content')
    <style>
        .card-footer,
        .progress {
            display: none;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Upload Content Trailer</li>
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
                            <h4 class="card-title mb-4">Content Trailer Upload</h4>
                            {{-- <form id="active_form" method="POST" action="{{ route('ott-content.store') }}" --}}
                            {{-- enctype="multipart/form-data"> --}}
                            {{-- @csrf --}}

                            <div id="basic-example">

                                <h3>Media Details</h3>

                                <div>
                                    <div class="card-body">
                                        <div id="upload-container" class="text-center">
                                            <button id="browseFile" class="btn btn-primary">Browse File</button>
                                        </div>
                                        <div class="progress mt-3" style="height: 25px">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                                style="width: 75%; height: 100%">75%</div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            {{-- </form> --}}

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
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>

    <script type="text/javascript">
        let browseFile = $('#browseFile');
        let resumable = new Resumable({
            target: '{{ route('files.uploadtrailer') }}',
            query: {
                _token: '{{ csrf_token() }}',
                content_id: {{ $content_id }}
            }, // CSRF token
            fileType: ['mp4', 'webm'],
            chunkSize: 1 * 1024 * 1024,
            headers: {
                'Accept': 'application/json'
            },
            testChunks: false,
            throttleProgressCallbacks: 1,
        });

        resumable.assignBrowse(browseFile[0]);

        resumable.on('fileAdded', function(file) { // trigger when file picked
            showProgress();
            resumable.upload() // to actually start uploading.
        });

        resumable.on('fileProgress', function(file) { // trigger when file progress update
            updateProgress(Math.floor(file.progress() * 100));
        });

        resumable.on('fileSuccess', function(file, response) { // trigger when file upload complete
            response = JSON.parse(response)
            // $('#videoPreview').attr('src', response.path);
            // $('.card-footer').show();
            window.location.href = "/admin/ott-content/{{ $content_id }}";
        });

        resumable.on('fileError', function(file, response) { // trigger when there is any error
            alert('file uploading error.')
        });


        let progress = $('.progress');

        function showProgress() {
            progress.find('.progress-bar').css('width', '0%');
            progress.find('.progress-bar').html('0%');
            progress.find('.progress-bar').removeClass('bg-success');
            progress.show();
        }

        function updateProgress(value) {
            progress.find('.progress-bar').css('width', `${value}%`)
            progress.find('.progress-bar').html(`${value}%`)
        }

        function hideProgress() {
            progress.hide();
        }
    </script>
@endsection
