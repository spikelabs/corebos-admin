@extends("layouts.admin")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>Image</h2>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif

        <form enctype="multipart/form-data" method="POST" action="{{ $image ? route("update_image", ["id" => $image->id]) : route("create_image") }}">
            @csrf
            @if($image)
                @method("PUT")
            @endif
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Image information
                            </h2>
                        </div>
                        <div class="body">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="name" class="form-control" placeholder="Enter image name" @if($image) value="{{ $image->name }}" @endif>
                                </div>
                            </div>

                            <label for="dockerhub_image">Dockerhub Image</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="dockerhub_image" class="form-control" placeholder="Enter dockerhub image" @if($image) value="{{ $image->dockerhub_image }}" @endif>
                                </div>
                            </div>

                            <div>
                                <label for="sql_file">Sql schema</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input @if(empty($image)) required @endif type="file" name="sql_file" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button style="width: 100%; text-align: center" type="submit" class="btn btn-primary m-t-15 waves-effect">@if($image) Update @else Create @endif</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            $("#images").addClass("active");
        })
    </script>
@endsection