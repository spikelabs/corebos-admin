@extends("layouts.admin")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>Cluster</h2>
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

        <form enctype="multipart/form-data" method="POST" action="{{ $cluster ? route("update_cluster", ["id" => $cluster->id]) : route("create_cluster") }}">
            @csrf
            @if($cluster)
                @method("PUT")
            @endif
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Cluster information
                            </h2>
                        </div>
                        <div class="body">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="name" class="form-control" placeholder="Enter cluster name" @if($cluster) value="{{ $cluster->name }}" @endif>
                                </div>
                            </div>

                            <label for="ip_address">Ip address</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="ip_address" class="form-control" placeholder="Enter cluster ip address" @if($cluster) value="{{ $cluster->ip_address }}" @endif>
                                </div>
                            </div>

                            <label for="cluster_id">Cluster id</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="cluster_id" class="form-control" placeholder="Enter cluster id" @if($cluster) value="{{ $cluster->cluster_id }}" @endif>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button style="width: 100%; text-align: center" type="submit" class="btn btn-primary m-t-15 waves-effect">@if($cluster) Update @else Create @endif</button>
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
            $("#clusters").addClass("active");
        })
    </script>
@endsection