@extends("layouts.admin")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>Clients</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Filter your clients
                        </h2>
                    </div>
                    <div class="body">
                        <form action="{{ route("clients") }}">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input name="filter" type="text" class="form-control" @if(!empty($filter))value="{{ $filter }}" @endif>
                                            <label class="form-label">Name or company name or sub-domain filter</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="cluster_id" class="form-control show-tick">
                                                <option value="-1">Select Cluster</option>
                                                @foreach($clusters as $cluster)
                                                    <option value="{{$cluster->id}}" @if($cluster_id == $cluster->id) {{"selected"}} @endif>{{ $cluster->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="image_id" class="form-control show-tick">
                                                <option value="-1">Select Image</option>
                                                @foreach($images as $image)
                                                    <option value="{{$image->id}}" @if($image_id == $image->id) {{"selected"}} @endif>{{ $image->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
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
                            Clients overview
                        </h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>Sub-domain</th>
                                <th>Details</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $index => $detail)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $detail->name }}</td>
                                    <td>{{ $detail->company_name }}</td>
                                    <td>{{ $detail->sub_domain }}</td>
                                    <td><a href="{{ route("client", ["id" => $detail->id]) }}" class="btn btn-primary waves-effect">View</a></td>
                                    <td><a href="{{ route("delete_client", ["id" => $detail->id]) }}" class="btn btn-danger waves-effect">Delete</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $clients->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $(document).ready(function () {
            $("#clients").addClass("active");
        })
    </script>
@endsection