@extends('frontEnd.layouts.hostel.master')
@section('title','Hostel Product')
@section('content')
 <section class="product-section_hostel">
        <div class="container">
            <div class="sorting-section">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="category-breadcrumb d-flex align-items-center">
                            <a href="">Hostel Products</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="category-product">
                        @forelse($products as $key => $value)
                            <div class="product_item wist_item">
                                <div class="product_item_inner">
                                    @if ($value->old_price)
                                        <div class="discount">
                                            <p>@php $discount=(((($value->old_price)-($value->wholesale_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% Discount</p>
                                        </div>
                                    @endif
                                    <div class="pro_img">
                                        <a href="{{ route('product', $value->slug) }}">
                                            <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                                alt="{{ $value->name }}" />
                                        </a>
                                    </div>
                                    <div class="pro_des">
                                        <div class="pro_name">
                                            <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
                                        </div>
                                        <div class="pro_price">
                                            @if ($value->variable_count > 0 && $value->type == 0)
                                                <p>
                                                    @if ($value->variable->old_price)
                                                        <del>৳ {{ $value->variable->old_price }}</del>
                                                    @endif
                                                    ৳ {{ $value->variable->wholesale_price }}
                                                </p>
                                            @else
                                                <p>
                                                    @if ($value->old_price)
                                                        <del>৳ {{ $value->old_price }}</del>
                                                    @endif
                                                    ৳ {{ $value->wholesale_price }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="pro_btn">
                                            @if ($value->variable_count > 0 && $value->type == 0)
                                             <button data-id="{{$value->id}}" class="hover-zoom quick_view cart_btn" title=" Order Now"><i class="fa-solid fa-shopping-cart"></i> Order Now</button>
                                            @else
                                            <div class="cart_btn">
                                                <form action="{{ route('cart.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $value->id }}" />
                                                    <button type="submit"><i class="fa-solid fa-shopping-cart"></i>Add To Cart</button>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        <div class="no-found">
                            <img src="{{asset('public/frontEnd/images/not-found.png')}}" alt="">
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_paginate">
                        {{ $products->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection