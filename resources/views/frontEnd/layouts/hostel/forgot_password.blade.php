@extends('frontEnd.layouts.master')
@section('title', 'Hostel Forgot Password')
@section('content')
    <section class="section-padding page-margin">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-sm-12">
                    <div class="auth-inner">
                        <h5 class="title">Forgot Password</h5>
                        <form action="{{ route('hostel.forgot.verify') }}" method="POST" data-parsley-validate="">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone Number<span>*</span></label>
                                <input type="text" class="form-control  {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                    placeholder="Enter Phone Number *" name="phone" value="{{ old('phone') }}" required>
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <button class="btn-submit d-block">Submit</button>
                            </div>

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
