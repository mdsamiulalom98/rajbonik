@extends('backEnd.layouts.master') 
@section('title','Shipping Discount Create') 
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('shippingdiscount.index')}}" class="btn btn-primary waves-effect waves-light btn-sm rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Shipping Discount Create</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('shippingdiscount.store')}}" method="POST" class="row" data-parsley-validate="" >
                        @csrf                        
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="min_amount" class="form-label">Min Shopping</label>
                                <input type="number" class="form-control @error('min_amount') is-invalid @enderror" name="min_amount" value="{{ old('min_amount') }}" id="min_amount" />
                                @error('min_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->         
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="max_amount" class="form-label">Max Shopping</label>
                                <input type="number" class="form-control @error('max_amount') is-invalid @enderror" name="max_amount" value="{{ old('max_amount') }}" id="max_amount" />
                                @error('max_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="discount" class="form-label">Discount</label>
                                <input type="number" class="form-control @error('discount') is-invalid @enderror" name="discount" value="{{ old('discount') }}" id="discount" />
                                @error('discount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6 mb-3">
                            <div class="form-group">
                                <label for="status" class="d-block form-label">Status</label>
                                <label class="switch">
                                    <input type="checkbox" value="1" name="status" checked />
                                    <span class="slider round"></span>
                                </label>
                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div>
                            <input type="submit" class="btn btn-success" value="Submit" />
                        </div>
                    </form>
                </div>
                <!-- end card-body-->
            </div>
            <!-- end card-->
        </div>
        <!-- end col-->
    </div>
</div>
@endsection 
@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
@endsection
