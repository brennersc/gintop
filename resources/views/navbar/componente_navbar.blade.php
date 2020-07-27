{{-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm"> --}}
<nav class="navbar navbar-expand-lg navbar-light bg-defaut shadow">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/home') }}">
            <img alt="Logo" width="150px" height="40px" src="../storage/imagens/logo-toptecnologia.png">
        </a>
        
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav ml-auto">
                    @auth

                    <li @if($current=="home" ) class="nav-item active" @else class="nav-item" @endif>
                        <a class="nav-link" href="/home">Home </a>
                    </li>

                    <li @if($current=="empresa" ) class="nav-item active" @else class="nav-item" @endif>
                        <a class="nav-link" href="/empresa">Empresas</a>
                    </li>
                    <li @if($current=="evento" ) class="nav-item active" @else class="nav-item" @endif>
                        <a class="nav-link" href="/evento">Eventos</a>
                    </li>
                    <li @if($current=="usuario" ) class="nav-item active" @else class="nav-item" @endif>
                        <a class="nav-link" href="/usuario">Usuários</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            v-pre>{{ Auth::user()->name }} <span class="caret"></span></a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    {{-- @if (Route::has('register'))
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                    @endif --}}
                    @endauth
                </ul>
            </div>        
    </div>
</nav>