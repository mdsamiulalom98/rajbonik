@extends('backEnd.layouts.master')
@section('title', 'Hostel Overview')
@section('css')
    <link href="{{ asset('public/backEnd') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('hostel.index') }}" class="btn btn-primary rounded-pill">Hostel List</a>
                        <form method="post" action="{{ route('hostel.adminlog') }}" class="d-inline" target="_blank">
                            @csrf
                            <input type="hidden" value="{{ $profile->id }}" name="hidden_id">
                            <button type="button" class="btn btn-info rounded-pill change-confirm"
                                title="Login as rider"><i class="fe-log-in"></i> Login</button>
                        </form>
                    </div>
                    <h4 class="page-title">Hostel Overview</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-4 col-xl-4">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="{{ asset($profile->image) }}" class="rounded-circle avatar-lg img-thumbnail"
                            alt="profile-image">

                        <h4 class="mb-2">{{ $profile->name }}</h4>

                        <a href="tel:{{ $profile->phone }}"
                            class="btn btn-success btn-xs waves-effect mb-2 waves-light">Call</a>
                        <a href="mailto:{{ $profile->email }}"
                            class="btn btn-danger btn-xs waves-effect mb-2 waves-light">Email</a>

                        <div class="text-start mt-3">
                            <h4 class="font-13 text-uppercase">About Me :</h4>
                            <table class="table">
                                <tbody>
                                    <tr class="text-muted mb-2 font-13">
                                        <td>Full Name </td>
                                        <td class="ms-2">{{ $profile->name }}</td>
                                    </tr>

                                    <tr class="text-muted mb-2 font-13">
                                        <td>Mobile </td>
                                        <td class="ms-2">{{ $profile->phone }}</td>
                                    </tr>

                                    <tr class="text-muted mb-1 font-13">
                                        <td>Address </td>
                                        <td class="ms-2">{{ $profile->address }}</td>
                                    </tr>
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end card -->

            </div> <!-- end col-->
        </div>
        <!-- end row-->

    </div> <!-- container -->

    </div> <!-- content -->
@endsection


@section('script')
    <script src="{{ asset('public/backEnd/') }}/assets/libs/parsleyjs/parsley.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-validation.init.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-advanced.init.js"></script>
@endsection
