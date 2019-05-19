@extends("layouts.admin")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>Clusters</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Filter your clusters
                        </h2>
                    </div>
                    <div class="body">
                        <form action="{{ route("clusters") }}">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="filter" type="text" class="form-control" @if(!empty($filters["filter"]))value="{{ $filters["filter"] }}" @endif>
                                            <label class="form-label">Name or ip address or cluster id</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <button type="submit" class="btn btn-primary btn-lg m-l-15 waves-effect">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Clusters overview
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Ip address</th>
                                <th>Cluster id</th>
                                <th>View</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clusters as $index => $detail)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $detail->name }}</td>
                                    <td>{{ $detail->ip_address }}</td>
                                    <td>{{ $detail->cluster_id }}</td>
                                    <td><a href="{{ route("cluster", ["id" => $detail->id]) }}" class="btn btn-primary waves-effect">View</a></td>
                                    <td><a class="btn btn-danger waves-effect">Delete</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $clusters->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            $("#clusters").addClass("active");
        })
    </script>
@endsection