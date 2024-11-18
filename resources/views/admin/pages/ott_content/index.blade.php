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
                                                    <th>UUID</th>
                                                    <th>Title</th>
                                                   
                                                    <th>Root Category</th>
                                                    <th>Sub Category</th>
                                                    <th>Sub Sub Category</th>
                                                    <th>Series</th>
                                                    <th>Poster</th>
                                                    <th>Status</th>
                                                    <th>Access</th> 
                                                    <th>Action</th>
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                @foreach ($ott_contents as $item)
                                                    <tr>
                                                        <input type="hidden" class="serdelet_val"
                                                            value={{ $item->id }}>
                                                        <td>{{ Str::limit($item->uuid,5) }}</td>
                                                        <td>{{ $item->title }}</td> 
                                                        <td>{{ $item->rootCategory->title }}</td> 
                                                        <td>{{ $item->subCategory->title }}</td> 
                                                        <td>{{ $item->subSubCategory->title }}</td> 
                                                        <td>{{ $item->ottSeries->title }}</td> 
                                                        <td><img height="50px" width="100px"
                                                                src="{{ url('' . $item->poster) }}"
                                                                alt="{{ $item->title }}"></td> 
                                                        <td> 
                                                            @if ($item->status == "Published")
                                                            <span class="badge badge-pill badge-soft-success font-size-12">{{ $item->status }}</span>  
                                                            @else
                                                            <span class="badge badge-pill badge-soft-danger font-size-12">{{ $item->status }}</span> 
                                                            @endif
                                                        </td>
                                                        
                                                        <td>
                                                        
                                                        @if ($item->access == "Premium")
                                                        <span class="badge badge-pill badge-soft-danger font-size-12">{{ $item->access }}</span>  
                                                        @else
                                                        <span class="badge badge-pill badge-soft-primary font-size-12">{{ $item->access }}</span> 
                                                        @endif

                                                        </td> 
                                                        <td>
                                                            <a class="btn btn-warning mdi mdi-book-edit-outline"
                                                                href="{{ route('ott-content.show', $item->id) }}"></a>
                                                            <a class="btn btn-danger delete mdi mdi-delete" href="#"></a>
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
                            url: '/admin/ott-content-delete/' + data, 
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
 
        });

    </script>
@endsection
