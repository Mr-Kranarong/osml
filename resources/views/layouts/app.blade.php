<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Admin Template by Tooplate.com</title>
    <!--

    Template 2108 Dashboard

	http://www.tooplate.com/view/2108-dashboard

    -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <!-- https://fonts.google.com/specimen/Open+Sans -->
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/fullcalendar.min.css">
    <!-- https://fullcalendar.io/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/tooplate.css">
</head>

<body id="reportsPage">
    <div class="" id="home">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-xl navbar-light bg-light">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <i class="fas fa-3x fa-tachometer-alt tm-site-icon"></i>
                            <h1 class="tm-site-title mb-0">{{ config('app.name', 'Laravel') }}</h1>
                        </a>
                        <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mx-auto">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="#">Home
                                        <span class="sr-only">(current)</span>
                                    </a>
                                </li>

                                @auth
                                    @if (Auth::user()->hasAccess())
                                    <li class="nav-item dropdown {{ Request::is('product*') || Request::is('promotion*') || Request::is('coupon*') ? 'active' : '' }}">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Store Function
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item {{ Request::is('product*') ? 'active' : '' }}" href="#">Product Management</a>
                                            <a class="dropdown-item {{ Request::is('promotion*') ? 'active' : '' }}" href="#">Promotion Pairing</a>
                                            <a class="dropdown-item {{ Request::is('coupon*') ? 'active' : '' }}" href="#">Coupon</a>
                                        </div>
                                    </li>
                                    <li class="nav-item {{ Request::is('user*') ? 'active' : '' }}">
                                        <a class="nav-link" href="products.html">User Management</a>
                                    </li>
                                    @endif
                                @endauth
                                
                                @auth
                                <li class="nav-item {{ Request::is('orders_reviews*') ? 'active' : '' }}">
                                    <a class="nav-link" href="accounts.html">Orders & Reviews</a>
                                </li>
                                    @if (!Auth::user()->hasAccess())
                                    <li class="nav-item {{ Request::is('cart*') ? 'active' : '' }}">
                                        <a class="nav-link" href="accounts.html">Cart</a>
                                    </li>
                                    @endif

                                    @if (Auth::user()->hasAccess())
                                <li class="nav-item {{ Request::is('statistics*') ? 'active' : '' }}">
                                    <a class="nav-link" href="accounts.html">Statistics</a>
                                </li>
                                <li class="nav-item {{ Request::is('settings*') ? 'active' : '' }}">
                                    <a class="nav-link" href="accounts.html">Settings</a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                            <ul class="navbar-nav ml-auto">
                                @guest
                                <li class="nav-item dropdown  {{ Request::is('login*') || Request::is('register*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Sign-in
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item {{ Request::is('login*') ? 'active' : '' }}" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @if (Route::has('register'))
                                <a class="dropdown-item {{ Request::is('register*') ? 'active' : '' }}" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        </div>
                    </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <!-- row -->
            <div class="row tm-content-row tm-mt-big">
                @yield('content')
            </div>
            <footer class="row tm-mt-small">
                <div class="col-12 font-weight-light">
                    <p class="d-inline-block tm-bg-black text-white py-2 px-4">
                        Copyright &copy; {{ \Carbon\Carbon::now()->year }} {{ Config::get('app.name') }}
                    </p>
                </div>
            </footer>
        </div>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- https://jquery.com/download/ -->
    <script src="js/moment.min.js"></script>
    <!-- https://momentjs.com/ -->
    <script src="js/utils.js"></script>
    <script src="js/Chart.min.js"></script>
    <!-- http://www.chartjs.org/docs/latest/ -->
    <script src="js/fullcalendar.min.js"></script>
    <!-- https://fullcalendar.io/ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
    <script src="js/tooplate-scripts.js"></script>
    <script>
        let ctxLine,
            ctxBar,
            ctxPie,
            optionsLine,
            optionsBar,
            optionsPie,
            configLine,
            configBar,
            configPie,
            lineChart;
        barChart, pieChart;
        // DOM is ready
        $(function () {
            updateChartOptions();
            drawLineChart(); // Line Chart
            drawBarChart(); // Bar Chart
            drawPieChart(); // Pie Chart
            drawCalendar(); // Calendar

            $(window).resize(function () {
                updateChartOptions();
                updateLineChart();
                updateBarChart();
                reloadPage();
            });
        })
    </script>
</body>
</html>