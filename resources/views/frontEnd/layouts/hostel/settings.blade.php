@extends('frontEnd.layouts.hostel.master')
@section('title','Hostel Settings')
@section('content')
	<div class="page-header">
		<h5>Hostel Settings</h5>
	</div>
	<div class="page-content">
		<div class="row">
			<div class="col-sm-10">
				<div class="user-settings">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs" id="setting-tab" role="tablist">
                              <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-tab-pane" type="button" role="tab">Basic Info</button>
                              </li>
                             {{-- <li class="nav-item" role="presentation">
                                <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank-tab-pane" type="button" role="tab">Bank Account</button>
                              </li>
                              <li class="nav-item" role="presentation">
                                <button class="nav-link" id="mobilebank-tab" data-bs-toggle="tab" data-bs-target="#mobilebank-tab-pane" type="button" role="tab">Mobile Banking</button>
                              </li>
                              <li class="nav-item" role="presentation">
                                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment-tab-pane" type="button" role="tab">Default Payment Method</button>
                              </li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="basic-tab-pane" role="tabpanel">
                          <form action="{{ route('hostel.basic_update') }}" method="POST"
                                enctype="multipart/form-data" data-parsley-validate="">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="{{ $profile->id }}" name="hidden_id">
                                    <!-- col end -->
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="address">Full Address</label>
                                            <textarea type="text" rows="2" class="form-control" placeholder="Full Address" name="address" rows="4">{{ $profile->address }}</textarea>
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->

                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="image" class="imagelabel">Profile Picture</label>
                                            <input type="file" class="form-control" name="image">
                                            <img src="{{ asset($profile->image) }}" class="small_img" alt="">
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="btn-submit d-block">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                          </div>
                          <!-- tab item end -->
                           {{--<div class="tab-pane fade" id="bank-tab-pane" role="tabpanel">
                            <form action="{{ route('hostel.payment_method') }}" method="POST"
                                enctype="multipart/form-data" data-parsley-validate="">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="{{ $profile->id }}" name="hidden_id">
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="bank_id">Bank Name</label>
                                            <select class="form-control select2 bank_id" name="bank_id" id="bank_id">
                                                <option value="">Select..</option>
                                              
                                            </select>
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="branch" id="branch">Branch</label>
                                            <input type="text" value="{{$method?$method->branch:''}}" name="branch" class="form-control">
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="routing">Routing</label>
                                            <input type="number" name="routing" id="routing"  value="{{$method?$method->routing:''}}" class="form-control">
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="account_name">Account Name</label>
                                            <input type="text" name="account_name" id="account_name"  value="{{$method?$method->account_name:''}}" class="form-control">
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="account_number">Account No</label>
                                            <input type="number"  value="{{$method?$method->account_number:''}}" name="account_number" id="account_number" class="form-control">
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="btn-submit d-block">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                           </div>
                           <!-- tab item end -->
                           <div class="tab-pane fade" id="mobilebank-tab-pane" role="tabpanel">
                            <form action="{{ route('hostel.payment_method') }}" method="POST"
                                enctype="multipart/form-data" data-parsley-validate="">
                                @csrf
                                <div class="row">
                                    <input type="hidden" value="{{ $profile->id }}" name="hidden_id">
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="bkash">Bkash</label>
                                            <input type="number"  value="{{$method?$method->bkash:''}}" name="bkash" id="bkash" class="form-control">
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="nagad">Nagad</label>
                                            <input type="number"  value="{{$method?$method->nagad:''}}" name="nagad" id="nagad" class="form-control">
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="rocket">Rocket</label>
                                            <input type="number"  value="{{$method?$method->rocket:''}}" name="rocket" id="rocket" class="form-control">
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="btn-submit d-block">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                           </div>
                           <!-- tab item end -->
                           <div class="tab-pane" id="payment-tab-pane" role="tabpanel">
                          <form action="{{ route('hostel.basic_update') }}" method="POST"
                                enctype="multipart/form-data" data-parsley-validate="">
                                @csrf
                                <input type="hidden" value="{{ $profile->id }}" name="hidden_id">
                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <select class="form-control select2" name="default_method" id="default_method">
                                                <option value="bank" {{$profile->default_method == 'bank'? 'selected':''}}>Bank</option>
                                                <option value="bkash" {{$profile->default_method == 'bkash'? 'selected':''}}>Bkash</option>
                                                <option value="nagad" {{$profile->default_method == 'nagad'? 'selected':''}}>Nagad</option>
                                                <option value="rocket" {{$profile->default_method == 'rocket'? 'selected':''}}>Rocket</option>
                                                <option value="cash" {{$profile->default_method == 'cash'? 'selected':''}}>Cash</option>
                                            </select>
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <select class="form-control select2 " name="payment_type" id="payment_type">
                                                <option value="per" {{$profile->payment_type == 'per'? 'selected':''}}>As Per Request</option>
                                                <option value="daily" {{$profile->payment_type == 'daily'? 'selected':''}}>Daily</option>
                                                <option value="weekly" {{$profile->payment_type == 'weekly'? 'selected':''}}>Weekly</option>
                                            </select>
                                        </div>
                                        <!-- form group -->
                                    </div>
                                    <!-- col end -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="btn-submit d-block">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                          </div>--}}
                          <!-- tab item end -->
                        </div>
                      </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('script')
 
@endpush