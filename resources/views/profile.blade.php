@extends("layouts.admin")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>BASIC FORM ELEMENTS</h2>
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

        <form method="POST" action="{{ route("update_profile") }}">
            @csrf
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                General information
                            </h2>
                        </div>
                        <div class="body">
                            <label for="email">Email</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input required type="email" name="email" class="form-control" placeholder="Enter new email" value="{{ $email }}">
                                </div>
                            </div>

                            <label for="password">Password</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="password" name="password" class="form-control" placeholder="Enter new password" value="">
                                </div>
                            </div>

                            <button style="width: 100%; text-align: center" type="submit" class="btn btn-primary m-t-15 waves-effect">Update</button>

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
            $("#profile").addClass("active");
        })
    </script>
@endsection