@extends('layouts.auth')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="{{ url('/') }}">
                        @if ($clientLogoUrl !== '')
                            <img src="{{ $clientLogoUrl }}" alt="{{ $clientAppName }}" />
                        @endif
                    </a>
                </div>
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle mb-5">Input your new password.</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}" />

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input id="email" type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus />
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input id="password" type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" placeholder="New password" name="password" required autocomplete="new-password" />
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input id="password-confirm" type="password" class="form-control form-control-xl" placeholder="Confirm new password" name="password_confirmation" required autocomplete="new-password" />
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Reset Password</button>
                </form>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right"></div>
        </div>
    </div>
@endsection
