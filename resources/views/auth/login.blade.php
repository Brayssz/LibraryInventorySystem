@extends('layouts.auth-layout')

@section('title', 'LRMDS')

@section('content')
    <div class="account-content">
        <div class="login-wrapper login-new">
            <div class="container">
                <div class="login-content user-login">
                    <div class="login-logo">
                        <img src="img/logo.png" alt="img">
                        <a href="index.html" class="login-logo logo-white">
                            <img src="img/logo.png" alt="">
                        </a>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="login-userset">
                            <div class="login-userheading">
                                <h3>Sign In</h3>
                                <h4>Access the LRMDS panel using your email and passcode.</h4>
                            </div>
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <i class="feather-alert-octagon flex-shrink-0 me-2"></i>
                                        <div>
                                            {{ $error }}
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="form-login">
                                <label class="form-label">Email Address</label>
                                <div class="form-addons">
                                    <input type="text" class="form-control" name="email" required>
                                    <img src="img/icons/mail.svg" alt="img">
                                </div>
                            </div>
                            <div class="form-login">
                                <label>Password</label>
                                <div class="pass-group">
                                    <input type="password" class="pass-input" name="password" required>
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                            </div>
                            <div class="form-login authentication-check">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox">
                                            <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                                                <input type="checkbox" name="remember">
                                                <span class="checkmarks"></span>Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a class="fw-bold" href="email-verification">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-login">
                                <button class="btn btn-login" type="submit">Sign In</button>
                            </div>
                           

                        </div>
                    </form>

                </div>
                <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                    <p>Copyright &copy; 2025 Koronadal City. All rights reserved</p>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#two-factor-modal').modal('show');
        });
    </script>
    
@endpush
