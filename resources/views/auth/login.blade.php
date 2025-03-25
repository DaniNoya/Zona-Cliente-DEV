@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

@section('auth_body')
    <div class="custom-auth-container">
        <form action="{{ $loginUrl }}" method="post">
            @csrf

            {{-- User field --}}
            <div class="input-group mb-3">
                <input type="user" name="user" class="form-control @error('user') is-invalid @enderror"
                    value="{{ old('user') }}" placeholder="Usuario" autofocus>

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                </div>

                @error('user')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Password field --}}
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password-field"
                    placeholder="Contraseña">

                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                    </div>
                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <script>
            document.getElementById('toggle-password').addEventListener('click', function() {
                const passwordField = document.getElementById('password-field');
                const icon = this.querySelector('i');
                
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
            </script>

            {{-- Login field --}}
            <div class="row">
                <div class="col-7">
                    <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">
                            Recuerdame
                        </label>
                    </div>
                </div>

                <div class="col-7">
                    <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                        <input type="checkbox" name="privacy" id="privacy" {{ old('privacy') ? 'checked' : '' }}>
                        <label for="privacy">
                            He leído y acepto las 
                            <a href="https://globalfunpartners.com/politica-de-privacidad/" target="_blank" style="color: #007bff; text-decoration: none;">
                                políticas de privacidad
                            </a>
                        </label>
                    </div>
                    @error('privacy')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>                
            </div>

            <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                Entrar
            </button>
        </form>
    </div>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($passResetUrl)
        <p class="my-0">
            <a href="{{ $passResetUrl }}">
                He olvidado mi contraseña
            </a>
        </p>
    @endif
@stop
