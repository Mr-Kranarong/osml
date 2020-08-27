<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Admin</title>
    <!--

    Template 2108 Dashboard

	http://www.tooplate.com/view/2108-dashboard

    -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <!-- https://fonts.google.com/specimen/Open+Sans -->
    <link rel="stylesheet" href="{{ url('css/fontawesome.min.css') }}">
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="{{ url('css/fullcalendar.min.css') }}">
    <!-- https://fullcalendar.io/ -->
    <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="{{ url('css/tooplate.css') }}">
</head>

<body id="reportsPage">
    <div class="" id="home">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-xl navbar-light bg-light">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <i class="fas fa-3x fa-tachometer-alt tm-site-icon"></i>
                            <h1 class="tm-site-title mb-0">{{ Config::get('app.name') }}</h1>
                        </a>
                        <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}"
                                        href="{{ route('home') }}">{{ __('text.Home') }}
                                        <span class="sr-only">(current)</span>
                                    </a>
                                </li>

                                @auth
                                    @if(Auth::user()->hasAccess())
                                        <li
                                            class="nav-item dropdown {{ Request::is('product*') || Request::is('promotion*') || Request::is('coupon*') || Request::is('user*') ? 'active' : '' }}">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                                role="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                {{ __('text.StoreFunction') }}
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item {{ Request::is('user*') ? 'active' : '' }}"
                                            href="{{route('user')}}">{{ __('text.UserManagement') }}</a>
                                                <a class="dropdown-item {{ Request::is('product*') ? 'active' : '' }}"
                                            href="{{route('product')}}">{{ __('text.ProductManagement') }}</a>
                                                <a class="dropdown-item {{ Request::is('promotion*') ? 'active' : '' }}"
                                                    href="#">{{ __('text.PromotionPairing') }}</a>
                                                <a class="dropdown-item {{ Request::is('coupon*') ? 'active' : '' }}"
                                                    href="#">{{ __('text.Coupon') }}</a>
                                            </div>
                                        </li>
                                    @endif
                                @endauth

                                @auth
                                    <li
                                        class="nav-item {{ Request::is('orders_reviews*') ? 'active' : '' }}">
                                        <a class="nav-link"
                                            href="#">{{ __('text.OrdersReviews') }}</a>
                                    </li>
                                    @if(!Auth::user()->hasAccess())
                                        <li
                                            class="nav-item {{ Request::is('cart*') ? 'active' : '' }}">
                                            <a class="nav-link" href="#">{{ __('text.Cart') }}</a>
                                        </li>
                                    @endif

                                    @if(Auth::user()->hasAccess())
                                        <li
                                            class="nav-item {{ Request::is('statistics*') ? 'active' : '' }}">
                                            <a class="nav-link"
                                                href="#">{{ __('text.Statistics') }}</a>
                                        </li>
                                        <li
                                            class="nav-item {{ Request::is('settings*') ? 'active' : '' }}">
                                            <a class="nav-link" href="#">{{ __('text.Settings') }}</a>
                                        </li>
                                    @endif
                                @endauth
                            </ul>
                            <ul class="navbar-nav ml-auto">
                                @guest
                                    <li
                                        class="nav-item dropdown  {{ Request::is('login*') || Request::is('register*') ? 'active' : '' }}">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('text.Signin') }}
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item {{ Request::is('login*') ? 'active' : '' }}"
                                                href="{{ route('login') }}">{{ __('text.Login') }}</a>
                                            @if(Route::has('register'))
                                                <a class="dropdown-item {{ Request::is('register*') ? 'active' : '' }}"
                                                    href="{{ route('register') }}">{{ __('text.Register') }}</a>
                                            @endif
                                        </div>
                                    </li>
                                @else
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }} <span class="caret"></span>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item {{ Request::is('profile*') ? 'active' : '' }}"
                                                href="#">{{ __('text.EditProfile') }}</a>

                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                {{ __('text.Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}"
                                                method="POST" style="display: none;">
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
            <footer class="row tm-mt-small mt-5">
                <div class="col-12 font-weight-light">
                    <div class="row tm-bg-black">
                        <p class="d-inline-block text-white py-2 px-4 pr-5 col-md-6 col-xs-12">
                            {{ __('text.Address') }} <br>
                            123/15 Something road, Basmic ASD, sdadp aldw adwad,a wddwalk awd, 1231415 <br>
                            Tel. 05618508, Email shite.store@toilet.shop
                        </p>
                        <p class="d-inline-block text-white py-2 px-4 col-md-6 col-xs-12 pl-5 my-auto text-right">
                            {{ __('text.Language') }} <a
                                href="{{ url('lang/en') }}">EN</a> | <a
                                href="{{ url('lang/th') }}">TH</a> <br>
                            Copyright &copy; {{ \Carbon\Carbon::now()->year }}
                            {{ Config::get('app.name') }} <br>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ url('js/jquery-3.3.1.min.js') }}"></script>
    <!-- https://jquery.com/download/ -->
    <script src="{{ url('js/moment.min.js') }}"></script>
    <!-- https://momentjs.com/ -->
    <script src="{{ url('js/utils.js') }}"></script>
    <script src="{{ url('js/Chart.min.js') }}"></script>
    <!-- http://www.chartjs.org/docs/latest/ -->
    <script src="{{ url('js/fullcalendar.min.js') }}"></script>
    <!-- https://fullcalendar.io/ -->
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
    <!-- https://getbootstrap.com/ -->
    <script src="{{ url('js/tooplate-scripts.js') }}"></script>
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
    @yield('script')
</body>

</html>
