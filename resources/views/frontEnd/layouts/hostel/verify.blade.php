@extends('frontEnd.layouts.master')
@section('title', 'Hostel Account Verify')
@section('content')
    <section class="section-padding page-margin">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-sm-12">
                    <div class="alert alert-danger alert-dismissible">
                         <!-- <p>অনুগ্রহপূর্বক আপনার মোবাইল নাম্বারে মেসেজ চেক করুন এবং OTP কোডটি এখানে প্রবেশ করুন।</p> -->
                        @if(Session::get('verify_phone'))
                        <p>Your Verify OTP Is : {{App\Models\Hostel::where('phone',Session::get('verify_phone'))->first()->verify}}</p>
                        @endif
                        
                    </div>
                    <div class="auth-inner">
                        <h5 class="title">Account Verify</h5>

                        <form action="{{ route('hostel.account.verify') }}" method="POST" data-parsley-validate="">
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
@endpush
