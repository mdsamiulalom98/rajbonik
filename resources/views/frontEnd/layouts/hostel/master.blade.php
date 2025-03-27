<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', '') - {{ $generalsetting->name }} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset($generalsetting->favicon) }}">
    @stack('seo')
    @stack('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/') }}/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('public/frontEnd/') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('public/frontEnd/') }}/css/toastr.min.css">
    <!-- toastr -->
    <link rel="stylesheet" href="{{ asset('public/frontEnd/') }}/css/select2.min.css">

    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/style.css?v=1.0.3') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/responsive.css?v=1.0.3') }}" />
    <!-- responsive css -->
    <!-- icons -->
    <link href="{{ asset('public/backEnd/') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- toastr css -->
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/assets/css/toastr.min.css" />
    <!-- custom css -->
    <link href="{{ asset('public/backEnd/') }}/assets/css/custom.css" rel="stylesheet" type="text/css" />
</head>
<style>
    ul.user-header-menu li a.notify-icon span {
        position: absolute;
        top: -4px;
        right: -8px;
        font-size: 12px !important;
        background: var(--primary-color);
        color: #fff;
        height: 20px;
        width: 20px;
        text-align: center;
        border-radius: 50px;
        line-height: 1.5;
    }

    .margin-shopping {
        margin-right: 0px !important;
    }

    body {
        background: #E8F6FC;
    }

    @media only screen and (min-width: 320px) and (max-width: 767px) {
        .modal-view-hostel {
            padding: 10px 8px;
            height: 100vh !important;
            overflow-y: auto !important;
        }
    }
</style>

<body>
    @php $subtotal = Cart::instance('shopping')->subtotal(); @endphp
    <div class="user-panel">
        <div class="user-sidebar">
            <div class="website-logo">
                <a href="{{ route('hostel.dashboard') }}">
                    <img src="{{ asset($generalsetting->dark_logo) }}" alt="">
                </a>
            </div>
            <div class="user-info">
                <div class="user-img">
                    <a href="{{ route('hostel.dashboard') }}">
                        <img src="{{ asset(Auth::guard('customer')->user()->image) }}" alt="">
                    </a>
                </div>
                <h5>{{ Str::limit(Auth::guard('customer')->user()->name, 15) }}</h5>
                <p>ID: {{ Auth::guard('customer')->user()->hostel_id }}</p>
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li><a href="{{ route('hostel.dashboard') }}"
                            class="{{ request()->is('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge"></i>
                            Dashboard</a></li>

                    <li><a href="{{ route('hostel.order') }}"
                            class="{{ request()->is('hostel/order') ? 'active' : '' }}"><i
                                class="fa-solid fa-cart-plus"></i> My Order</a></li>

                    </li>
                    <li><a href="{{ route('hostel.settings') }}"
                            class="{{ request()->is('settings') ? 'active' : '' }}"><i class="fa-solid fa-cog"></i>
                            Settings</a></li>
                    <li><a href="{{ route('hostel.change_pass') }}"
                            class="{{ request()->is('change-password') ? 'active' : '' }}"><i
                                class="fa-solid fa-key"></i> Change
                            Password</a></li>
                    <li><a href="{{ route('hostel.logout') }}"
                            onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i
                                class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    <form id="logout-form" action="{{ route('hostel.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>
        <!-- sidebar end -->
        <div class="user-container">
            <div class="user-header">
                <div class="user-header-right d-flex">
                    <ul class="user-header-menu">
                        <li class="dropdown"><a class="notify-icon" role="button" data-bs-toggle="dropdown"><i
                                    class="fa-solid fa-bell"></i></a>
                            <ul class="dropdown-menu nofity-dropdown dropdown-menu-end">
                                <li><a href="{{ route('hostel.order') }}" class="value->status == 0 ? 'fw-bold' : ''">
                                        All Order</a></li>
                            </ul>
                        </li>

                    </ul>
                    <div class="user-login-info d-flex dropdown">
                        <div class="dropdown">
                            <div class="user-quick d-flex" role="button">
                                <img src="{{ asset(Auth::guard('customer')->user()->image) }}" alt="">
                                <p>{{ Str::limit(Auth::guard('customer')->user()->shop_name, 15) }} <i
                                        class="fa-solid fa-caret-down"></i></p>
                            </div>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('hostel.hprofile') }}"><i class="fa-solid fa-home"></i> My
                                        Account</a></li>
                                <li><a href="{{ route('hostel.settings') }}"><i class="fa-solid fa-cog"></i>
                                        Setting</a></li>
                                <li><a href="{{ route('hostel.change_pass') }}"><i class="fa-solid fa-key"></i> Change
                                        Password</a></li>
                                <li>
                                    <a href="{{ route('hostel.logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form2').submit();">
                                        <i class="fa-solid fa-sign-out"></i> Logout
                                        <form id="logout-form2" action="{{ route('hostel.logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header end -->
            <div class="user-mheader">
                <div class="user-toggle">
                    <button><i class="fa-solid fa-bars"></i></button>
                </div>
                <div class="user-logo">
                    <a href="{{ route('hostel.dashboard') }}">
                        <img src="{{ asset($generalsetting->dark_logo) }}" alt="">
                    </a>
                </div>
                <div class="mobile-search">
                    <button class="search_toggle"><i class="fa-solid fa-search"></i></button>
                </div>
            </div>
            <div class="consignment_msearch">
                <div class="user-search">
                    <i class="fa-solid fa-times"></i>
                    <form>

                        <input type="text" placeholder="Search order" class="msearch_click mkeyword">
                        <button><i class="fa-solid fa-search"></i></button>
                    </form>
                    <div class="search_result"></div>
                </div>
            </div>
            <div class="user-content">
                @yield('content')
            </div>
        </div>
    </div>
    {{-- <div class="user-footer">
        <ul>
            <li>
                <a href="" class="{{ request()->is('support') ? 'active' : '' }}">
                    <i class="fa-solid fa-message"></i>
                    <p>Support</p>
                </a>
            </li>
            <li  class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{route('hostel.dashboard')}}">
                    <i class="fa-solid fa-home"></i>
                    <p>Home</p>
                </a>
            </li>

            <li  class="{{ request()->is('profile') ? 'active' : '' }}">
                <a href="{{route('hostel.profile')}}">
                    <i class="fa-solid fa-user"></i>
                    <p>Profile</p>
                </a>
            </li>
        </ul>
    </div> --}}

    <div id="custom-modal"></div>
    <div id="page-overlay"></div>
    <div id="loading">
        <div class="custom-loader"></div>
    </div>

    <script src="{{ asset('public/frontEnd/') }}/js/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('public/frontEnd/') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('public/frontEnd/') }}/js/select2.min.js"></script>
    <script src="{{ asset('public/frontEnd/') }}/js/popper.min.js"></script>
    <!-- custom script -->
    <script src="{{ asset('public/frontEnd/') }}/js/toastr.min.js"></script>
    <script src="{{ asset('public/frontEnd/js/mobile-menu.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/wsit-menu.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/mobile-menu-init.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/wow.min.js') }}"></script>
    <!-- feather icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{ asset('public/frontEnd/js/script.js') }}"></script>
    <script>
        new WOW().init();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Toastr -->
    {!! Toastr::message() !!}
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
        $(document).ready(function() {
            $('.user-toggle').on('click', function() {
                $('.user-sidebar').addClass('active');
                $('#page-overlay').show();
            });
            $('.search_toggle').on('click', function() {
                $('.consignment_msearch').addClass('active');
                $('#page-overlay').show();
            });
            $("#page-overlay,.fa-times").on("click", function() {
                $("#page-overlay").hide();
                $(".user-sidebar").removeClass("active");
                $(".consignment_msearch").removeClass("active");
                $("#custom-modal").hide();
            });
        });
    </script>

    {!! Toastr::message() !!} @stack('script')
    <script>
        $(".invoice_data").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('invoice.data') }}",
                    success: function(data) {
                        if (data) {
                            $("#custom-modal").html(data);
                            $("#custom-modal").show();
                            $("#loading").hide();
                            $("#page-overlay").show();
                        }
                    },
                });
            }
        });
    </script>
    <script>
        function cart_content() {
            $.ajax({
                type: "GET",
                url: "{{ route('hostel.order.cart_content') }}",
                dataType: "html",
                success: function(hostel_cart) {
                    $("#cartTable").html(hostel_cart);
                },
            });
        }
        $(".order_create").on("click", function() {
            var id = 1;
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('order.create.hostel') }}",
                    success: function(data) {
                        if (data) {
                            $("#custom-modal").html(data);
                            $("#custom-modal").show();
                            $("#loading").hide();
                            $("#page-overlay").show();
                        }
                    },
                });
            }
        });

        $(".order_edit_hostel").on("click", function() {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('order.edit.hostel') }}",
                    success: function(data) {
                        $("#custom-modal").html(data);
                        $("#custom-modal").show();
                        $("#loading").hide();
                        $("#page-overlay").show();
                    },
                });
            }
        });
    </script>

    @stack('script')

</body>

</html>
