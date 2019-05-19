@extends("layouts.admin")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>Client</h2>
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

        <form enctype="multipart/form-data" method="POST" action="{{ !empty($data['client']) ? route("update_client", ["id" => $data["client"]->id]) : route("create_client") }}">
            @csrf
            @if(!empty($data["client"]))
                @method("PUT")
            @endif
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                General information
                            </h2>
                        </div>
                        <div class="body">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="name" class="form-control" placeholder="Enter client name" @if(!empty($data['client'])) value="{{ $data['client']->name }}" @endif>
                                </div>
                            </div>

                            <label for="email">Email</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="email" name="email" class="form-control" placeholder="Enter client email" @if(!empty($data['client'])) value="{{ $data['client']->email }}" @endif>
                                </div>
                            </div>

                            <label for="company_name">Company name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="company_name" class="form-control" placeholder="Enter client company name" @if(!empty($data['client'])) value="{{ $data['client']->company_name }}" @endif>
                                </div>
                            </div>

                            <label for="sub_domain">Sub domain</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="sub_domain" class="form-control" placeholder="Enter client sub domain" @if(!empty($data['client'])) value="{{ $data['client']->sub_domain }}" @endif>
                                </div>
                            </div>

                            <label for="description">Description</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea required name="description" rows="2" class="form-control no-resize" placeholder="Enter client description">@if(!empty($data['client'])){{ $data['client']->description }}@endif</textarea>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <label for="cluster_id">Cluster</label>
                                    <select name="cluster_id" class="form-control @if (empty($data['client'])) {{ "show-tick" }} @else {{ "disabled" }} @endif">
                                        @foreach($data['clusters'] as $cluster)
                                            <option value="{{$cluster->id}}">{{ $cluster->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label for="image_id">Image</label>
                                    <select name="image_id" class="form-control @if (empty($data['client'])) {{ "show-tick" }} @else {{ "disabled" }} @endif">
                                        @foreach($data['images'] as $image)
                                            <option value="{{$image->id}}">{{ $image->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button style="width: 100%; text-align: center" type="submit" class="btn btn-primary m-t-15 waves-effect">@if(!empty($data['client'])) Update @else Create @endif</button>
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
            $("#clients").addClass("active");
        })
    </script>
@endsection