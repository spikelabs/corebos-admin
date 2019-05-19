<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Corbos Admin</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset("images/favicon.ico") }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset("plugins/bootstrap/css/bootstrap.css") }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset("plugins/node-waves/waves.css") }}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset("plugins/animate-css/animate.css") }}" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="{{ asset("plugins/morrisjs/morris.css") }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset("css/style.css") }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset("css/themes/all-themes.css") }}" rel="stylesheet" />

    <link href="{{ asset("plugins/jquery-spinner/css/bootstrap-spinner.css") }}" rel="stylesheet">

</head>

<body class="theme-red">
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{ route("clients") }}">Corebos Admin</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="{{ asset("images/user.png") }}" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ auth()->user()->name }}</div>
                    <div class="email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li id="clusters">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">list</i>
                            <span>Cluster</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ route("clusters") }}">Clusters</a>
                            </li>
                            <li>
                                <a href="{{ route("cluster_form") }}">New</a>
                            </li>
                        </ul>
                    </li>
                    <li id="images">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">list</i>
                            <span>Image</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ route("images") }}">Images</a>
                            </li>
                            <li>
                                <a href="{{ route("image_form") }}">New</a>
                            </li>
                        </ul>
                    </li>
                    <li id="clients">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">list</i>
                            <span>Client</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ route("clients") }}">Clients</a>
                            </li>
                            <li>
                                <a href="{{ route("client_form") }}">New</a>
                            </li>
                        </ul>
                    </li>
                    <li id="profile">
                        <a href="{{ route("get_profile") }}">
                            <i class="material-icons">create</i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("logout") }}">
                            <i class="material-icons">lock_outline</i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="legal">
                <div class="copyright">
                    &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.
                </div>
            </div>
            <!-- #Footer -->
        </aside>
    </section>

    <section class="content">
        @yield('content')
    </section>

    <!-- Jquery Core Js -->
    <script src="{{ asset("plugins/jquery/jquery.min.js") }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset("plugins/bootstrap/js/bootstrap.js") }}"></script>

    <!-- Select Plugin Js -->
    <script src="{{ asset("plugins/bootstrap-select/js/bootstrap-select.js") }}"></script>

    <script src="{{ asset("plugins/jquery-spinner/js/jquery.spinner.js") }}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset("plugins/jquery-slimscroll/jquery.slimscroll.js") }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset("plugins/node-waves/waves.js") }}"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="{{ asset("plugins/jquery-countto/jquery.countTo.js") }}"></script>

    <!-- Morris Plugin Js -->
    <script src="{{ asset("plugins/raphael/raphael.min.js") }}"></script>
    <script src="{{ asset("plugins/morrisjs/morris.js") }}"></script>

    <!-- ChartJs -->
    <script src="{{ asset("plugins/chartjs/Chart.bundle.js") }}"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="{{ asset("plugins/flot-charts/jquery.flot.js") }}"></script>
    <script src="{{ asset("plugins/flot-charts/jquery.flot.resize.js") }}"></script>
    <script src="{{ asset("plugins/flot-charts/jquery.flot.pie.js") }}"></script>
    <script src="{{ asset("plugins/flot-charts/jquery.flot.categories.js") }}"></script>
    <script src="{{ asset("plugins/flot-charts/jquery.flot.time.js") }}"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ asset("plugins/jquery-sparkline/jquery.sparkline.js") }}"></script>

    <!-- Custom Js -->
    <script src="{{ asset("js/admin.js") }}"></script>
    {{--<script src="{{ asset("js/admin/pages/index.js") }}"></script>--}}

    <!-- Demo Js -->
    <script src="{{ asset("js/demo.js") }}"></script>


    @yield('script')
</body>

</html>
