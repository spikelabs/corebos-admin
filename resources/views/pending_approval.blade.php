@extends("layouts.admin")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>Pending Approval</h2>
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

        <form enctype="multipart/form-data" method="POST" action="{{ route("approve_pending_approval") }}">
            @csrf

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                General information
                            </h2>
                        </div>
                        <input type="hidden" name="id" value="{{ $data["pending_approval"]->id }}">
                        <div class="body">
                            <label for="name">Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input disabled type="text" class="form-control" value="{{ $data['pending_approval']->name }}">
                                </div>
                            </div>

                            <label for="email">Email</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input disabled type="email" class="form-control" value="{{ $data['pending_approval']->email }}">
                                </div>
                            </div>

                            <label for="company_name">Company name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input disabled type="text" class="form-control" value="{{ $data['pending_approval']->company_name }}">
                                </div>
                            </div>

                            <label for="sub_domain">Sub domain</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="text" name="sub_domain" class="form-control" placeholder="Enter client sub domain">
                                </div>
                            </div>

                            <label for="description">Description</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea disabled required name="description" rows="2" class="form-control no-resize">{{ $data['pending_approval']->description }}</textarea>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <label for="cluster_id">Cluster</label>
                                    <select name="cluster_id" class="form-control show-tick">
                                        @foreach($data['clusters'] as $cluster)
                                            <option value="{{$cluster->id}}">{{ $cluster->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label>Image</label>
                                    <select class="form-control disabled">
                                        <option>{{ $data["image"]->name }}</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button style="width: 100%; text-align: center" type="submit" class="btn btn-primary m-t-15 waves-effect">Approve</button>
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
            $("#pending_approvals").addClass("active");
        })
    </script>
@endsection