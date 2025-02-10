@extends('frontEnd.layouts.master')
@section('title', 'Forgot Verify')
@section('content')
    <section class="section-padding page-margin">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-sm-12">
                    <div class="alert alert-danger alert-dismissible">
                        <p>অনুগ্রহপূর্বক আপনার মোবাইল নাম্বারে মেসেজ চেক করুন এবং OTP কোডটি এখানে প্রবেশ করুন।</p>
                    </div>
                    <div class="auth-inner">
                        <h5 class="title">Forgot Verify</h5>

                        <form action="{{ route('hostel.forgot.store') }}" method="POST" data-parsley-validate="">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="otp" class="form-label">OTP<span>*</span></label>
                                <input type="number" class="form-control  {{ $errors->has('otp') ? 'is-invalid' : '' }}"
                                    placeholder="Enter OTP Number *" name="otp" value="{{ old('otp') }}" required>
                                @if ($errors->has('otp'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('otp') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="form-label">New Password <span>*</span></label>
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
                            <div class="form-group">
                                <button class="btn-submit d-block">Submit</button>
                            </div>
                            <!-- form group -->
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
