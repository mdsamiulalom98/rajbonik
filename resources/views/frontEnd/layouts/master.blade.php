<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') - {{ $generalsetting->name }}</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" alt="Websolution IT" />
    <meta name="author" content="Websolution IT" />
    <link rel="canonical" href="" />
    @stack('seo') @stack('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/mobile-menu.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
    <!-- toastr css -->
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/assets/css/toastr.min.css" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/wsit-menu.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/style.css?v=1.0.7') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/responsive.css?v=1.0.6') }}" />
    <script src="{{ asset('public/frontEnd/js/jquery-3.7.1.min.js') }}"></script>
    @foreach ($pixels as $pixel)
        <!-- Facebook Pixel Code -->
        <script>
            !(function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = "2.0";
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s);
            })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
            fbq("init", "{{ $pixel->code }}");
            fbq("track", "PageView");
        </script>
        <noscript>
            <img height="1" width="1" style="display: none;"
                src="https://www.facebook.com/tr?id={{ $pixel->code }}&ev=PageView&noscript=1" />
        </noscript>
        <!-- End Facebook Pixel Code -->
    @endforeach

    @foreach ($gtm_code as $gtm)
        <!-- Google tag (gtag.js) -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    "gtm.start": new Date().getTime(),
                    event: "gtm.js"
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != "dataLayer" ? "&l=" + l : "";
                j.async = true;
                j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
                f.parentNode.insertBefore(j, f);
            })
            (window, document, "script", "dataLayer", "GTM-{{ $gtm->code }}");
        </script>
        <!-- End Google Tag Manager -->
    @endforeach
</head>

<body class="gotop">
    {{-- @if ($coupon)
        <div  class="coupon-section alert alert-dismissible fade show" >
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="coupon-code">
                            <p>Get {{$coupon->amount}} {{$coupon->type == 1 ? "%" : "Tk"}} Discount use the coupon code <span id="couponCode">{{$coupon->coupon_code}}</span>
                            <button onclick="copyCouponCode()"> <i class="fas fa-copy"></i>
                            </button></p>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif --}}
    @php
        $subtotal = Cart::instance('shopping')->subtotal();
        $cartcount = \Gloudemans\Shoppingcart\Facades\Cart::instance('shopping')->count();
    @endphp
    <div class="mobile-menu">
        <div class="mobile-menu-logo">
            <div class="logo-image">
                <img src="{{ asset($generalsetting->dark_logo) }}" alt="" />
            </div>
            <div class="mobile-menu-close">
                <i class="fa fa-times"></i>
            </div>
        </div>
        <ul class="first-nav">
            @foreach ($categories as $scategory)
                <li class="parent-category">
                    <a href="{{ route('category', $scategory->slug) }}" class="menu-category-name">
                        <img src="{{ asset($scategory->image) }}" alt="" class="side_cat_img" />
                        {{ $scategory->name }}
                    </a>
                    @if ($scategory->subcategories->count() > 0)
                        <span class="menu-category-toggle">
                            <i class="fa fa-caret-down"></i>
                        </span>
                    @endif
                    <ul class="second-nav" style="display: none;">
                        @foreach ($scategory->subcategories as $subcategory)
                            <li class="parent-subcategory">
                                <a href="{{ route('subcategory', $subcategory->slug) }}"
                                    class="menu-subcategory-name">{{ $subcategory->name }}</a>
                                @foreach ($subcategory->childcategories as $childcat)
                            <li class="childcategory"><a href="{{ route('products', $childcat->slug) }}"
                                    class="menu-childcategory-name">{{ $childcat->name }}</a></li>
                        @endforeach
                </li>
            @endforeach
        </ul>
        </li>
        @endforeach
        </ul>
        <div class="mobilemenu-bottom">
            <ul>
                @if (Auth::guard('customer')->user())
                    <li class="for_order">
                        <a href="{{ route('customer.account') }}">
                            <i class="fa-regular fa-user"></i>
                            {{ Str::limit(Auth::guard('customer')->user()->name, 14) }}
                        </a>
                    </li>
                @else
                    <li class="for_order">
                        <a href="{{ route('customer.login') }}">Login / Sign Up</a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('customer.order_track') }}"> Order Track </a>
                </li>
                <li>
                    <a href="{{ route('coupon.view') }}">Coupon </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}">Contact Us </a>
                </li>
            </ul>
        </div>
    </div>
    <header id="navbar_top">
        <!-- mobile header start -->
        <div class="mobile-header">
            <div class="mobile-logo">
                <div class="menu-bar">
                    <a class="toggle">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </div>
                <div class="menu-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset($generalsetting->dark_logo) }}"
                            alt="" /></a>
                </div>
                <div class="menu-bag">
                    <button class="cart-toggle-button margin-shopping">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="mobilecart-qty">{{ Cart::instance('shopping')->count() }}</span>
                    </button>
                </div>

            </div>
        </div>
        <div class="mobile-search main-search">
            <form action="{{ route('search') }}">
                <p class="mobile-daraz"><i data-feather="search"></i></p>
                <input type="text" placeholder="I am shopping for..." class="search_keyword search_click"
                    name="keyword" />
                <button>Search</button>
            </form>
            <div class="search_result"></div>
        </div>
        <!-- mobile header end -->
        <div class="main-header">
            <div class="logo-area">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="logo-header">
                                <div class="main-logo">
                                    <a href="{{ route('home') }}"><img src="{{ asset($generalsetting->dark_logo) }}"
                                            alt="" /></a>
                                </div>
                                <div class="main-search">
                                    <form action="{{ route('search') }}">
                                        <button><i data-feather="search"></i></button>
                                        <input type="text" placeholder="Search Product..."
                                            class="msearch_keyword msearch_click" name="keyword" />
                                    </form>
                                    <div class="search_result"></div>
                                </div>
                                <div class="header-list-items">
                                    <ul>
                                        <li class="track_btn">
                                            <a href="{{ route('customer.order_track') }}"> <i
                                                    class="fa fa-truck"></i></a>
                                        </li>
                                        @if (Auth::guard('customer')->user())
                                            <li class="for_order">
                                                <p>
                                                    <a href="{{ route('customer.account') }}">
                                                        <i class="fa-regular fa-user"></i>
                                                    </a>
                                                </p>
                                            </li>
                                        @else
                                            <li class="for_order">
                                                <p>
                                                    <a href="{{ route('customer.login') }}">
                                                        <i class="fa-regular fa-user"></i>
                                                    </a>
                                                </p>
                                            </li>
                                        @endif
                                          <li class="wish-dialog">
                                                <a href="{{route ('wishlist.show')}}">
                                                    <p class="margin-shopping">
                                                        <i class="fa-solid fa-heart"></i>
                                                        <span class="wish-qty">{{ Cart::instance('wishlist')->count() }}</span>
                                                    </p>
                                                </a>
                                            </li>

                                        <li class="cart-dialog" id="cart-qty">
                                            <button class="cart-toggle-button">
                                                <p class="margin-shopping">
                                                    <i class="fa-solid fa-cart-shopping"></i>
                                                    <span>{{ $cartcount }}</span>
                                                </p>
                                            </button>
                                            <!-- <div class="cshort-summary">
                                                    <ul>
                                                        @foreach (Cart::instance('shopping')->content() as $key => $value)
<li>
                                                            <a href=""><img src="{{ asset($value->options->image) }}" alt="" /></a>
                                                        </li>
                                                        <li><a href="">{{ Str::limit($value->name, 30) }}</a></li>
                                                        <li>Qty: {{ $value->qty }}</li>
                                                        <li>
                                                            <p>৳{{ $value->price }}</p>
                                                            <button class="remove-cart cart_remove" data-id="{{ $value->rowId }}"><i class="fa-regular fa-trash-can trash_icon" title="Delete this item"></i></button>
                                                        </li>
@endforeach
                                                    </ul>
                                                    <p><strong>SubTotal : ৳{{ $subtotal }}</strong></p>
                                                    <a href="{{ route('customer.checkout') }}" class="go_cart">Process To Order </a>
                                                </div> -->
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- logo area end -->

            <div class="menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="main-menu">
                                <ul>
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('category', $category->slug) }}">
                                                {{ $category->name }}
                                                @if ($category->subcategories->count() > 0)
                                                    <i class="fa-solid fa-angle-down cat_down"></i>
                                                @endif
                                            </a>
                                            @if ($category->subcategories->count() > 0)
                                                <div class="mega_menu">
                                                    @foreach ($category->subcategories as $subcat)
                                                        <ul>
                                                            <li>
                                                                <a href="{{ route('subcategory', $subcat->slug) }}"
                                                                    class="cat-title">
                                                                    {{ Str::limit($subcat->name, 25) }}
                                                                </a>
                                                            </li>
                                                            @foreach ($subcat->childcategories as $childcat)
                                                                <li>
                                                                    <a
                                                                        href="{{ route('products', $childcat->slug) }}">{{ $childcat->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- menu area end -->
        </div>
        <!-- main-header end -->
    </header>
    <div id="content">
        @yield('content')
    </div>
    <!-- content end -->
    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="footer-about">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset($generalsetting->dark_logo) }}" alt="" />
                            </a>
                            <p>{{ $contact->address }}</p>
                            <p><a href="tel:{{ $contact->hotline }}"
                                    class="footer-hotlint">{{ $contact->hotline }}</a></p>
                            <p><a href="mailto:{{ $contact->hotmail }}"
                                    class="footer-hotlint">{{ $contact->hotmail }}</a></p>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-3">
                        <div class="footer-menu">
                            <ul>
                                <li class="title "><a>Useful Link</a></li>
                                @foreach ($pages as $page)
                                    <li><a
                                            href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-sm-2">
                        <div class="footer-menu">
                            <ul>
                                <li class="title"><a>Customer Link</a></li>
                                <li><a href="{{ route('customer.register') }}">Register</a></li>
                                <li><a href="{{ route('customer.login') }}">Login</a></li>
                                <li><a href="{{ route('customer.forgot.password') }}">Forgot Password?</a></li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                                <li><a href="{{ route('hostel.login') }}"><strong
                                            style="border:1px solid pink; border-radius: 3px; padding:5px 12px;">Hostel
                                            Login</strong></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- col end -->
                    <div class="col-sm-3">
                        <div class="footer-menu">
                            <ul>
                                <li class="title text-center"><a>Follow Us</a></li>
                            </ul>
                            <ul class="social_link">
                                @foreach ($socialicons as $value)
                                    <li>
                                        <a href="{{ $value->link }}"><i class="{{ $value->icon }}"></i></a>
                                    </li>
                                @endforeach
                            </ul>
                            <ul>
                                <li class="title text-center mb-0"><a class="mb-0">Delivery Partner</a></li>
                                <li class="delivery-partner">
                                    <img src="{{ asset('public/frontEnd/images/delivery-partner.png') }}"
                                        alt="">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="copyright">
                            <p>Copyright © {{ date('Y') }} {{ $generalsetting->name }}. All rights reserved.
                                Developed By <a href="https://websolutionit.com">Websolution IT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--=====-->
    <!-- ============footer nav start =============== -->
    <div class="footer_nav">
        <ul>
            <li>
                <a href="{{ route('home') }}">
                    <span><i class="fa-solid fa-house-chimney"></i></span> <span>Home</span>
                </a>
            </li>

            <li>
                <a class="toggle">
                    <span>
                        <i class="fa-solid fa-list"></i>
                    </span>
                    <span>Category</span>
                </a>
            </li>


            <li>
                <button class="cart-toggle-button">
                    <span>
                        <i class="fa-solid fa-cart-arrow-down"></i>
                    </span>
                    <span>Cart <b class="mobilecart-qty nav-count-top">{{ $cartcount }}</b></span>
                </button>
            </li>


            <li class="main-menu-li">
                <a href="{{route ('wishlist.show')}}" class="main-menu-link">
                    <span>
                        <i class="fa-regular fa-heart"></i>
                    </span>
                    <span>Wishlist <b class="mobile__wishlist nav-count-top">
                             {{ Cart::instance('wishlist')->content()->count() }}</b> 
                    </span>
                </a>
            </li>
            @if (Auth::guard('customer')->user())
                <li>
                    <a href="{{ route('customer.account') }}">
                        <span>
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <span>Account</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('customer.login') }}">
                        <span>
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <span>Login</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <!-- ============footer nav end ============== -->
    <div class="fixed_whats">
        <a href="https://api.whatsapp.com/send?phone={{ $contact->whatsapp }}" target="_blank"><i
                class="fa-brands fa-whatsapp"></i></a>
    </div>

    <div class="scrolltop" style="">
        <div class="scroll">
            <i class="fa fa-angle-up"></i>
        </div>
    </div>

    <!-- /. fixed sidebar -->

    <div id="custom-modal"></div>
    <div id="page-overlay"></div>
    <div id="loading">
        <div class="custom-loader"></div>
    </div>


    <!-- cart sidebar -->
    <div class="mini-cart-wrapper">
        @include('frontEnd.layouts.partials.mini_cart')
    </div>
    <!-- cart sidebar -->

    <script src="{{ asset('public/frontEnd/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
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


    <script src="{{ asset('public/backEnd/') }}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!} @stack('script')
<script>
   $(document).on("change", "input[name='shipping_charge']", function() {
    var id = $(this).val(); // Get selected radio value (which is the shipping charge ID)

    $.ajax({
        type: "GET",
        url: "{{ route('shipping.charge') }}",
        data: { id: id },
        dataType: "html",
        success: function(response) {
            $(".table__section").html(response); // Update table section with response
        },
        error: function() {
            alert("Something went wrong! Please try again.");
        }
    });
});

</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('popup');
            const closePopup = document.getElementById('closePopup');

            // Show popup after 3 seconds
            setTimeout(() => {
                popup.style.display = 'flex'; // Show popup
            }, 3000);

            // Close popup on close button click
            closePopup.addEventListener('click', () => {
                popup.style.display = 'none'; // Hide popup
            });
        });
    </script>
    <script>
        $(".quick_view").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('quickview') }}",
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
    <!-- quick view end -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.detailsFormSubmit', function(e) {
                e.preventDefault();
                var colors = $('.variable_color');
                var color = $(".variable_color:checked").data('color');
                var size = $(".variable_size:checked").data('size');
                const productId = $(this).data('id');
                const addcart = $(this).data('addcart');
                if (colors.length > 0) {
                    console.log('this color: ' + colors);
                    if (!color) {
                        toastr.warning("Please select a color before adding to the cart.", "Warning");
                        $('.selector-item_label').addClass('red');
                        return;
                    }
                } else {
                    console.log('nothing');
                }
                $.ajax({
                    url: '{{ route('ajax.cart.store') }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: productId,
                        color: color,
                        size: size,
                        addcart: addcart
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            toastr.success("Product add to cart succfully", "Success");
                            if (response.redirect) {
                                window.location.href = '{{ route('customer.checkout') }}';
                            } else {
                                $("#page-overlay").show();
                                $(".mini-cart-wrapper").addClass("active");
                            }
                            return cart_count() + mobile_cart() + cart_summary() + mini_cart();
                        } else if (!response.success) {
                            toastr.error("Product stock over", "Sorry");
                        } else {
                            console.log(response.message || 'Failed to update cart');
                        }
                    },
                    error: function() {
                        console.log('An error occurred while updating the cart.');
                    },
                });
            });
        });
    </script>
    <script>
        $(".addcartbutton").on("click", function() {
            var id = $(this).data("id");
            var qty = 1;
            if (id) {
                $.ajax({
                    cache: "false",
                    type: "GET",
                    url: "{{ url('add-to-cart') }}/" + id + "/" + qty,
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                            toastr.success("Success", "Product add to cart successfully");
                            $(".mini-cart-wrapper").addClass("active");
                            $("#page-overlay").show();
                            return cart_count() + mobile_cart() + mini_cart();

                        }
                    },
                });
            }
        });

        $(".cart_store").on("click", function() {
            var id = $(this).data("id");
            var qty = $(this).parent().find("input").val();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id,
                        qty: qty ? qty : 1
                    },
                    url: "{{ route('cart.store') }}",
                    success: function(data) {
                        if (data) {
                            toastr.success("Success", "Product add to cart succfully");
                            return cart_count() + mobile_cart() + mini_cart();
                        }
                    },
                });
            }
        });

        $(document).on('click', '.cart_remove', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.remove') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart() + cart_summary() + mini_cart() + viewCart_ajax() + cart__list();
                        }
                    },
                });
            }
        });

       
        // address create
        $(document).on('click', '#createNewAddress', function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ route('customer.address.modal') }}",
                success: function(response) {
                    if (response.success) {
                        $(".address-create-modal").html(response.updatedHtml);
                    }
                },
            });
        });

         // address edit
        $(document).on('click', '#address___edit', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            $.ajax({
                type: "GET",
                data:{id:id},
                url: "{{ route('customer.address.edit.modal') }}",
                success: function(response) {
                    if (response.success) {
                        $(".address-create-modal").html(response.updatedHtml);
                    }
                },
            });
        });

        //delete address
         $(document).on('click', '#address___delete', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('address.remove') }}",
                    success: function(response) {
                        if (response) {
                           location.reload();
                        }
                    },
                });
            }
        });

        //modal close 
        $(document).on('click', '#addressModalClose', function(e) {
            e.preventDefault();
            $(".address-create-modal").text('');
        });

        $(document).on('click', '#createAddressButton', function(e) {
            e.preventDefault();
            var name = $('#name').val();
            var phone = $('#phone').val();
            var road_no = $('#road_no').val();
            var flat_no = $('#flat_no').val();
            var block = $('#block').val();
            var floor_no = $('#floor_no').val();
            var house_no = $('#house_no').val();
            var area_id = $('#area_id').val();
            var district = $('#district').val();
            if (phone) {
                $.ajax({
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: name,
                        phone: phone,
                        road_no: road_no,
                        flat_no: flat_no,
                        block: block,
                        floor_no: floor_no,
                        house_no: house_no,
                        area_id: area_id,
                        district: district
                    },
                    url: "{{ route('customer.address.create') }}",
                    success: function(response) {
                        if (response.status == 'success') {
                            toastr.success(response.message, "Success");
                            $(".address-create-modal").text('');
                            location.reload();
                        }
                    },
                });
            }
        }); 

        $(document).on('click', '#updateAddressButton', function(e) {
            e.preventDefault();
            var hidden_id = $('#hidden_id').val();
            var name = $('#name').val();
            var phone = $('#phone').val();
            var road_no = $('#road_no').val();
            var flat_no = $('#flat_no').val();
            var block = $('#block').val();
            var floor_no = $('#floor_no').val();
            var house_no = $('#house_no').val();
            var area_id = $('#area_id').val();
            var district = $('#district').val();
            if (phone) {
                $.ajax({
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: hidden_id,
                        name: name,
                        phone: phone,
                        road_no: road_no,
                        flat_no: flat_no,
                        block: block,
                        floor_no: floor_no,
                        house_no: house_no,
                        area_id: area_id,
                        district: district
                    },
                    url: "{{ route('customer.address.update') }}",
                    success: function(response) {
                        if (response.status == 'success') {
                            toastr.success(response.message, "Success");
                            $(".address-create-modal").text('');
                             location.reload();
                        }
                    },
                });
            }
        });

    // address change
        $(document).on('click', '#changeAddress', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                url: "{{ route('customer.address.change') }}",
                success: function(response) {
                    if (response.success) {
                        $("#customerAddresses").html(response.updatedHtml);
                    }
                },
            });
        });
        // address select
        $(document).on('click', '.customer-address-item', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (id) {
                $.ajax({
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: name,
                        id: id,
                    },
                    url: "{{ route('customer.address.select') }}",
                    success: function(response) {
                        if (response.success) {
                            $("#customerAddresses").html(response.updatedHtml);
                        }
                    },
                });
            }
        });
        // address select
        $(document).ready(function() {
            let date = new Date().toISOString().split('T')[0];
            return time_slots(date);
        });

        $(document).on('change', '#order_date', function(e) {
            e.preventDefault();
            var date = $(this).val();
            return time_slots(date);
        });


        function time_slots(date) {
            if (date) {
                $.ajax({
                    type: 'GET',
                    data: {
                        date: date,
                    },
                    url: "{{ route('timeslots') }}",
                    success: function(response) {
                        let options = '<option value="">Select Time</option>';
                        response.forEach(function(slot) {
                            options +=
                                `<option value="${slot.start_time}">${convertTo12Hour(slot.start_time)} - ${convertTo12Hour(slot.end_time)}</option>`;
                        });

                        $('#order_time').html(options);
                    },
                });
            }
        }

        function convertTo12Hour(time) {
            let [hours, minutes] = time.split(':');
            let period = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convert 0 to 12 for AM
            return `${hours}:${minutes} ${period}`;
        }

        $(document).on('click', '.cart_increment', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.increment') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart() + mini_cart() + viewCart_ajax() + cart__list();
                        }
                    },
                });
            }
        });
        $(document).on('click', '.cart_decrement', function(e) {
            e.preventDefault();
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.decrement') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart() + mini_cart() + viewCart_ajax() + cart__list();
                        }
                    },
                });
            }
        });

        function cart_count() {
            $.ajax({
                type: "GET",
                url: "{{ route('cart.count') }}",
                success: function(data) {
                    if (data) {
                        $("#cart-qty").html(data);
                    } else {
                        $("#cart-qty").empty();
                    }
                },
            });
        }

        function mobile_cart() {
            $.ajax({
                type: "GET",
                url: "{{ route('mobile.cart.count') }}",
                success: function(data) {
                    if (data) {
                        $(".mobilecart-qty").html(data);
                    } else {
                        $(".mobilecart-qty").empty();
                    }
                },
            });
        }

       

        function cart_summary() {
            $.ajax({
                type: "GET",
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $(".cart-summary").html(response);
                },
            });
        }

        function mini_cart() {
            $.ajax({
                type: "GET",
                url: "{{ route('mini.cart') }}",
                dataType: "html",
                success: function(data) {
                    $(".mini-cart-wrapper").html(data);
                },
            });
        }

        function viewCart_ajax() {
            $.ajax({
                type: "GET",
                url: "{{ route('viewCart.ajax') }}",
                dataType: "html",
                success: function(data) {
                    $("#cartlists").html(data);
                },
            });
        }

        function cart__list(){
            $.ajax({
                type: "GET",
                url: "{{ route('cart__list.ajax') }}",
                dataType: "html",
                success: function(data) {
                    $("#cart__list").html(data);
                },
            });
        }
    </script>
    <!-- cart js end -->
     <script>
            $('.wishlist_store').on('click', function() {
                var id = $(this).data('id');
                var qty = 1;
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: {
                            'id': id,
                            'qty': qty ? qty : 1
                        },
                        url: "{{ route('wishlist.store') }}",
                         dataType: "json",
                        success: function(data) {
                            if (data) {
                                toastr.success('success', 'Product added in wishlist');
                                return wishlist_count()+wishlist() + mobile__wishlist();
                            }
                        }
                    });
                }
            });
            $('.wishlist_remove').on('click', function() {
                var id = $(this).data('id');
                if (id) {
                    $.ajax({
                        type: "GET",
                        data: {
                            'id': id
                        },
                        url: "{{ route('wishlist.remove') }}",
                        success: function(data) {
                            if (data) {
                                return wishlist_count()+wishlist() + mobile__wishlist();
                            }
                        }
                    });
                }
            });
            function wishlist_count() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('wishlist.count') }}",
                    dataType: "html",
                    success: function(data) {
                        if (data) {
                            $(".wish-qty").html(data);
                        } else {
                            $(".wish-qty").empty();
                        }
                    }
                });
            };
             function mobile__wishlist() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('mobile.wishlist.count') }}",
                    success: function(data) {
                        if (data) {
                            $(".mobile__wishlist").html(data);
                        } else {
                            $(".mobile__wishlist").empty();
                        }
                    },
                });
            };

            function wishlist() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('wishlist.summary') }}",
                    dataType: "html",
                    success: function(data) {
                        if (data) {
                            $("#wishlist").html(data);
                        } else {
                            $("#wishlist").empty();
                        }
                    }
                });
            };
        </script>
        <!--Compare js -->
    <script>
        $(".search_click").on("keyup change", function() {
            var keyword = $(".search_keyword").val();
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
        $(".msearch_click").on("keyup change", function() {
            var keyword = $(".msearch_keyword").val();
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $("#loading").hide();
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });

        $(".search_click_banner").on("keyup change", function() {
            var keyword = $(this).val();
            var banner = 1;
            console.log(keyword);
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword,
                    banner: banner
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $("#loading").hide();
                        $(".search_result_banner").html(products);
                    } else {
                        $(".search_result_banner").empty();
                    }
                },
            });
        });
    </script>
    <!-- search js start -->
    <script>
        $(document).on('change', '.district', function(e) {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "{{ route('districts') }}",
                success: function(res) {
                    if (res) {
                        $(".area").empty();
                        $(".area").append('<option value="">Select..</option>');
                        $.each(res, function(key, value) {
                            $(".area").append('<option value="' + key + '" >' + value +
                                "</option>");
                        });
                    } else {
                        $(".area").empty();
                    }
                },
            });
        });
    </script>
    <script>
        $(".toggle").on("click", function() {
            $("#page-overlay").show();
            $(".mobile-menu").addClass("active");
        });

        $(".cart-toggle").on("click", function() {
            $(".mini-cart-wrapper").addClass("active");
        });

        $(document).on('click', '.cart-toggle-button', function(e) {
            e.preventDefault();
            $("#page-overlay").show();
            $(".mini-cart-wrapper").addClass("active");
        });

        $("#page-overlay").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
            $(".feature-products").removeClass("active");
            $(".mini-cart-wrapper").removeClass("active");
        });

        $(".mobile-menu-close").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
        });

        $(".mobile-filter-toggle").on("click", function() {
            $("#page-overlay").show();
            $(".feature-products").addClass("active");
        });

        $("#page-overlay").on("click", function() {
            $("#custom-modal").hide();
            $(".feature-products").addClass("active");
        });
        $(document).on('click', '.mini-close-button', function(e) {
            e.preventDefault();
            $(".mini-cart-wrapper").removeClass("active");
            $("#page-overlay").hide();
        });

        $(document).on('click', '.coupon__section', function(e) {
            e.preventDefault();
            $(".main__coupon_apply").toggle();
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".parent-category").each(function() {
                const menuCatToggle = $(this).find(".menu-category-toggle");
                const secondNav = $(this).find(".second-nav");

                menuCatToggle.on("click", function() {
                    menuCatToggle.toggleClass("active");
                    secondNav.slideToggle("fast");
                    $(this).closest(".parent-category").toggleClass("active");
                });
            });
            $(".parent-subcategory").each(function() {
                const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                const thirdNav = $(this).find(".third-nav");

                menuSubcatToggle.on("click", function() {
                    menuSubcatToggle.toggleClass("active");
                    thirdNav.slideToggle("fast");
                    $(this).closest(".parent-subcategory").toggleClass("active");
                });
            });
        });
    </script>

    <script>
        var menu = new MmenuLight(document.querySelector("#menu"), "all");

        var navigator = menu.navigation({
            selectedClass: "Selected",
            slidingSubmenus: true,
            // theme: 'dark',
            title: "ক্যাটাগরি",
        });

        var drawer = menu.offcanvas({
            // position: 'left'
        });
        document.querySelector('a[href="#menu"]').addEventListener("click", (evnt) => {
            evnt.preventDefault();
            drawer.open();
        });
    </script>

    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $(".scrolltop:hidden").stop(true, true).fadeIn();
            } else {
                $(".scrolltop").stop(true, true).fadeOut();
            }
        });
        $(function() {
            $(".scroll").click(function() {
                $("html,body").animate({
                    scrollTop: $(".gotop").offset().top
                }, "1000");
                return false;
            });
        });
    </script>
    <script>
        $(".filter_btn").click(function() {
            $(".filter_sidebar").addClass("active");
            $("body").css("overflow-y", "hidden");
        });
        $(".filter_close").click(function() {
            $(".filter_sidebar").removeClass("active");
            $("body").css("overflow-y", "auto");
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".logoslider").owlCarousel({
                margin: 0,
                loop: true,
                dots: false,
                nav: false,
                autoplay: true,
                autoplayTimeout: 6000,
                animateOut: "fadeOut",
                animateIn: "fadeIn",
                smartSpeed: 3000,
                autoplayHoverPause: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: false,
                        dots: false,
                    },
                    600: {
                        items: 1,
                        nav: false,
                        dots: false,
                    },
                    1000: {
                        items: 1,
                        nav: false,
                        loop: true,
                        dots: false,
                    },
                },
            });
        });
    </script>
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>

    <!-- Google Tag Manager (noscript) -->
    @foreach ($gtm_code as $gtm)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-{{ $gtm->code }}" height="0"
                width="0" style="display: none; visibility: hidden;"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endforeach

    <script>
        function copyCouponCode() {
            var couponCode = document.getElementById("couponCode").innerText;
            var tempInput = document.createElement("input");
            tempInput.value = couponCode;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999);
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            toastr.success('Coupon Code copied successfully!');
        }
    </script>
</body>

</html>
