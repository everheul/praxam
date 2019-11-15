{{--
    components.toprow
    arguments: $align (left,=right)
--}}
<div id="toprow" class="navbar navbar-expand-md navbar-light bg-faded appcolor">
    <div  class="container">
        {{-- the (invisible) hamburger --}}
        <button id="menu-toggler" class="navbar-toggler navbar-dark collapsed" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon cross"></span>
            <span class="navbar-toggler-icon hamburger"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @if ($align == 'left')
                <ul class="navbar-nav ml-auto">
            @else
                <ul class="navbar-nav ml-auto">
            @endif
                @guest
                {{-- Authentication Links --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    {{-- logoff & home --}}
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @if(Route::current()->getName() != 'home')
                                <a class="dropdown-item" href="{{ route('home') }}">Dashboard</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
    </div>
</div>
