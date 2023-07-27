@extends('layouts.app')

@section('content')


    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="text-center">
            <a href="#"><img src="assets/images/xlogo.png" alt="XPanel" style="width: 60px; margin-bottom: 30px;"></a>

        </div>
        <div class="form-group mb-3">
            <input id="email" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

            @error('username')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <input id="password" type="text" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

            @error('password')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <input id="home_url" type="text" class="form-control @error('home_url') is-invalid @enderror" name="home_url" required autocomplete="current-home-url">

            @error('home_url')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>
        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Login') }}
            </button>
        </div>
    </form>
@endsection
