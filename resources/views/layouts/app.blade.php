<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Admin</title>
    <!--

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

    {{-- extra --}}
    <link rel="stylesheet" href="{{ url('css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
</head>

<body id="reportsPage">
    <div class="" id="home">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-xl navbar-light bg-light rounded">
                        <a class="navbar-brand" style="width:20%" href="{{ url('/') }}">
                            {{-- <i class="fas fa-3x fa-tachometer-alt tm-site-icon"></i>
                            --}}
                            <img src="https://i.gifer.com/H0be.gif" style="max-width: 15%;width:fit-content;" alt="">
                            <h1 class="tm-site-title mb-0" style="width:fit-content">{{ Config::get('app.name') }}</h1>
                        </a>
                        <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}"
                                        href="{{ route('home') }}">{{ __('text.Home') }}
                                        <span class="sr-only">(current)</span>
                                    </a>
                                </li>

                                @auth
                                    @if (Auth::user()->hasAccess())
                                        <li
                                            class="nav-item dropdown {{ Request::is('product*') || Request::is('promotion*') || Request::is('coupon*') || Request::is('user*') ? 'active' : '' }}">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ __('text.StoreFunction') }}
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item {{ Request::is('user*') ? 'active' : '' }}"
                                                    href="{{ route('user.index') }}">{{ __('text.UserManagement') }}</a>
                                                <a class="dropdown-item {{ Request::is('product*') ? 'active' : '' }}"
                                                    href="{{ route('product.index') }}">{{ __('text.ProductManagement') }}</a>
                                                <a class="dropdown-item {{ Request::is('promotion*') ? 'active' : '' }}"
                                                    href="#">{{ __('text.PromotionPairing') }}</a>
                                                <a class="dropdown-item {{ Request::is('coupon*') ? 'active' : '' }}"
                                                    href="{{ route('coupon.index') }}">{{ __('text.Coupon') }}</a>
                                            </div>
                                        </li>
                                    @endif
                                @endauth

                                @auth
                                    <li class="nav-item {{ Request::is('orders_reviews*') ? 'active' : '' }}">
                                        <a class="nav-link" href="#">{{ __('text.OrdersReviews') }}</a>
                                    </li>

                                    @if (Auth::user()->hasAccess())
                                        <li class="nav-item {{ Request::is('statistics*') ? 'active' : '' }}">
                                            <a class="nav-link" href="#">{{ __('text.Statistics') }}</a>
                                        </li>
                                        <li class="nav-item {{ Request::is('settings*') ? 'active' : '' }}">
                                            <a class="nav-link" href="#">{{ __('text.Settings') }}</a>
                                        </li>
                                    @endif
                                @endauth

                                @if (!Auth::user() || !Auth::user()->hasAccess())
                                    <li class="nav-item {{ Request::is('cart*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{route('cart.index')}}">{{ __('text.Cart') }}</a>
                                    </li>
                                @endif
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
                                            @if (Route::has('register'))
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
                                                data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                                data-target="#update-self-modal-{{ Auth::id() }}"
                                                href="#">{{ __('text.EditProfile') }}</a>

                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             document.getElementById('logout-form').submit();">
                                                {{ __('text.Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
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
                    <div class="row tm-bg-black rounded">
                        <p class="d-inline-block text-white py-2 px-4 pr-5 col-md-6 col-xs-12">
                            {{ __('text.Address') }} <br>
                            123/15 Something road, Basmic ASD, sdadp aldw adwad,a wddwalk awd, 1231415 <br>
                            Tel. 05618508, Email shite.store@toilet.shop
                        </p>
                        <p class="d-inline-block text-white py-2 px-4 col-md-6 col-xs-12 pl-5 my-auto text-right">
                            {{ __('text.Language') }} <a href="{{ url('lang/en') }}">EN</a> | <a
                                href="{{ url('lang/th') }}">TH</a> <br>
                            Copyright &copy; {{ \Carbon\Carbon::now()->year }}
                            {{ Config::get('app.name') }} <br>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <iframe width="0%" height="0" scrolling="no" frameborder="no" allow="autoplay" id="scp"
    src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/2960154&color=%23ff5500&auto_play=true&hide_related=true&show_comments=false&show_user=false&show_reposts=false&show_teaser=false&visual=false"></iframe>
    <script src="{{ url('js/jquery-3.3.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    {{-- extra --}}
    <script src="{{ url('js/venobox.min.js') }}"></script>
    <script>
        document.body.addEventListener('mousedown', function() {
            var audio = new Audio("https://www.myinstants.com/media/sounds/nintendo-switch-the-click.mp3");
            audio.play()
        });

    </script>

    <script src="https://w.soundcloud.com/player/api.js"></script>
    <script>
        var widget = SC.Widget(document.getElementById("scp"));
        widget.bind(SC.Widget.FINISH, function() {
            widget.seekTo(0);
            widget.play();
        });
    </script>

    @yield('script')
</body>
{{-- <div data-video="w0byaC0F-i8" data-loop="1" data-playlist="" id="youtube-audio">
    <script src="https://www.youtube.com/iframe_api"></script>
    <script
        src="https://rawcdn.githack.com/Mr-Kranarong/Hidden-Youtube-Background-Music-JS/0d0d34dbb33614e1c99f85ad9899e2397f17770b/yt.js">
    </script>
</div> --}}
@auth
    {{-- update-self PROFILE MODAL AND FORM --}}
    <div class="modal fade" id="update-self-modal-{{ Auth::id() }}" tabindex="-1" role="dialog"
        aria-labelledby="update-self-modal-{{ Auth::id() }}-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update-self-modal-{{ Auth::id() }}-label">{{ __('text.EditingUser') }}:
                        {{ Auth::id() }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="document.getElementById('form-self-{{ Auth::id() }}').reset()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.update_self', Auth::user()) }}" method="POST" id="form-self-{{ Auth::id() }}">
                    @csrf
                    @method('put')

                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="self-name"
                                class="col-md-4 col-form-label text-md-right">{{ __('text.Name') }}</label>
                            <div class="col-md-6">
                                <input id="self-name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ Auth::user()->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="self-address"
                                class="col-md-4 col-form-label text-md-right">{{ __('text.Address') }}</label>

                            <div class="col-md-6">
                                <input id="self-address" type="text"
                                    class="form-control @error('address') is-invalid @enderror" name="address"
                                    value="{{ Auth::user()->address }}" autocomplete="address" autofocus>

                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="self-phone"
                                class="col-md-4 col-form-label text-md-right">{{ __('text.Phone') }}</label>

                            <div class="col-md-6">
                                <input id="self-phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ Auth::user()->phone }}" autocomplete="phone" autofocus>

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="self-email"
                                class="col-md-4 col-form-label text-md-right">{{ __('text.Email') }}</label>

                            <div class="col-md-6">
                                <input id="self-email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ Auth::user()->email }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <h6 class="modal-title" >{{__('text.ChangePassword')}} ({{__('text.Optional')}})</h6>
                        <hr>

                        <div class="form-group row">
                            <label for="self-password"
                                class="col-md-4 col-form-label text-md-right">{{ __('text.Password') }}</label>

                            <div class="col-md-6">
                                <input id="self-password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="********************">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('text.ConfirmPassword') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  placeholder="********************" autocomplete="new-password">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="reset()">{{ __('text.CancelChanges') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('text.SaveChanges') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END update-self PROFILE MODAL --}}
@endauth

</html>
