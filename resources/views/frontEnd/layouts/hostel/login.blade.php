@extends('frontEnd.layouts.master')
@section('title', 'Hostel Login')
@section('content')
    <section class="section-padding page-margin">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-sm-12">
                    <div class="auth-inner">
                        <h5 class="title">Hostel Login</h5>
                        <form action="{{route('customer.signin')}}" method="POST" data-parsley-validate="">
                            @csrf
                            <input type="hidden" name="customer_type" value="hostel">
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Email or Phone Number <span>*</span></label>
                                <input type="text" class="form-control  {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                    placeholder="Enter email or phone number *" name="phone" value="{{ old('phone') }}" required>
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password <span>*</span></label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }} password"
                                        placeholder="Enter Password" name="password" value="{{ old('password') }}" required>
                                    <span class="px-3 input-group-text toggle_password">
                                        <i class="fas fa-eye eye_icon"></i>
                                    </span>
                                </div>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- form group -->
                            <div class="form-group mb-3 text-end">
                                <a href="{{ route('customer.forgot.password') }}">Forgot Password?</a>
                            </div>
                            <div class="form-group">
                                <button class="btn-submit d-block">Login</button>
                            </div>
                            <!-- form group -->
                            {{--<p class="quick_link">
                                <strong>If you have no account?</strong>
                                <a href="{{ route('hostel.register') }}"> Create Account</a>
                            </p>--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ asset('public/frontEnd/') }}/js/parsley.min.js"></script>
    <script>
        $(".toggle_password").on("click", function() {
            var password_field = $('.password');
            var icon = $('.eye_icon');
            if (password_field.attr('type') === 'password') {
                password_field.attr('type', 'text');
                icon.removeClass('fa-eye');
                icon.addClass('fa-eye-slash');
            } else {
                password_field.attr('type', 'password');
                icon.removeClass('fa-eye-slash');
                icon.addClass('fa-eye');
            }
        });
    </script>
@endpush
