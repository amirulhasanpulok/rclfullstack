@extends('frontEnd.layouts.master')
@section('title', $details?->name)
@push('seo')
<meta name="app-url" content="{{ route('product', $details?->slug) }}" />
<meta name="robots" content="index, follow" />
<meta name="description" content="{{ $details?->meta_description }}" />
<meta name="keywords" content="{{ $details?->slug }}" />

<!-- Twitter Card data -->
<meta name="twitter:card" content="product" />
<meta name="twitter:site" content="{{ $details?->name }}" />
<meta name="twitter:title" content="{{ $details?->name }}" />
<meta name="twitter:description" content="{{ $details?->meta_description }}" />
<meta name="twitter:creator" content="{{route('home')}}" />
<meta property="og:url" content="{{ route('product', $details?->slug) }}" />
<meta name="twitter:image" content="{{ asset($details?->image->image) }}" />

<!-- Open Graph data -->
<meta property="og:title" content="{{ $details?->name }}" />
<meta property="og:type" content="product" />
<meta property="og:url" content="{{ route('product', $details?->slug) }}" />
<meta property="og:image" content="{{ asset($details?->image->image) }}" />
<meta property="og:description" content="{{ $details?->meta_description }}" />
<meta property="og:site_name" content="{{ $details?->name }}" />
@endpush

@push('css')
<style>
  @media (max-width: 991.98px) {  
    .chheckout-section {
        margin-top: 100px;
    }
}
    .mobile-search {
        display: none;
    }
 .footer_nav1 {
            display: none !important;
        }
    @media only screen and (min-width: 320px) and (max-width: 767px) {
        .footer_nav {
            display: none !important;
        }
       .footer_nav1 {
            display: block !important;
        }
    }
</style>
<style>
    .footer_nav1 {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #fff;
        box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .action-row {
        display: flex;
        gap: 10px;
    }

    .action-row button {
        flex: 1;
        padding: 12px 0;
        font-size: 15px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .add_cart_btn {
        background: #28a745;
    }

    .add_cart_btn:hover {
        background: #d2e900;
    }

    .order_now_btn {
        background: #c9eb0c;
        animation: pulse 1.5s infinite; /* kapakapi effect */
    }

    .order_now_btn:hover {
        background: #b5fd0d;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 rgba(17, 125, 184, 0.6);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 12px rgba(0, 233, 70, 0.6);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 rgba(17, 125, 184, 0.6);
        }
    }
</style>

<link rel="stylesheet" href="{{ asset('frontEnd/css/zoomsl.css') }}">
@endpush

@section('content')

<div class="container chheckout-section p-3">
    <div class="row">
        <div class="col-sm-6 position-relative">
            @if($details?->variable_count > 0 && $details?->type == 0)
            @if($details?->variable->old_price)
            <div class="discount">
                <p>@php $discount=(((($details?->variable->old_price)-
                    ($details?->variable->new_price))*100) / ($details?->variable->old_price))
                    @endphp
                    -{{ number_format($discount, 0) }}%</p>

            </div>
            @endif
            @else
            @if($details?->old_price)
            <div class="discount">
                <p>@php $discount=(((($details?->old_price)-($details?->new_price))*100) /
                    ($details?->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
            </div>
            @endif
            @endif

            <style>
                .zoom-container {
                    overflow: hidden;
                    position: relative;
                    cursor: zoom-in;
                }

                .zoom-image {
                    width: 100%;
                    height: auto;
                    transition: transform 0.4s ease-in-out, filter 0.3s ease-in-out;
                    transform-origin: center center;
                    /* keeps zoom centered */
                }

                .zoom-container:hover .zoom-image {
                    transform: scale(1.4);
                    /* zoom level */
                    filter: brightness(1.05);
                }
            </style>

            <!-- Variable product image -->
            @if($details?->variables->count() > 0)
            <div class="details_slider owl-carousel">
                @foreach ($details?->variables as $value)
                @if($value->image)
                <div class="dimage_item zoom-container">
                    <img src="{{ asset($value->image) }}" class="zoom-image block__pic" alt="Product Image">
                </div>
                @endif
                @endforeach
            </div>

            <div class="indicator_thumb @if ($details?->variables->count() > 5) thumb_slider owl-carousel @endif">
                @foreach ($details?->variables as $key => $value)
                @if(!empty($value->image))
                <div class="indicator-item" data-id="{{ $key }}">
                    <img src="{{ asset($value->image) }}" />
                </div>
                @endif
                @endforeach
            </div>

            <!-- Normal product image -->
            @else
            <div class="details_slider owl-carousel">
                @foreach ($details?->images as $value)
                @if($value->image)
                <div class="dimage_item zoom-container">
                    <img src="{{ asset($value->image) }}" class="zoom-image block__pic" alt="Product Image">
                </div>
                @endif
                @endforeach
            </div>

            <div class="indicator_thumb @if ($details?->images->count() > 5) thumb_slider owl-carousel @endif">
                @foreach ($details?->images as $key => $value)
                @if($value->image)
                <div class="indicator-item" data-id="{{ $key }}">
                    <img src="{{ asset($value->image) }}" />
                </div>
                @endif
                @endforeach
            </div>
            @endif



        </div>
        <div class="col-sm-6">
            <div class="details_right">
                <div class="breadcrumb">
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><span>/</span></li>
                        <li><a href="{{ url('/category/' . $details?->category->slug) }}">{{
                                $details?->category->name }}</a>
                        </li>
                        @if ($details?->subcategory)
                        <li><span>/</span></li>
                        <li><a href="#">{{ $details?->subcategory ?
                                $details?->subcategory->subcategoryName : '' }}</a>
                        </li>
                        @endif @if ($details?->childcategory)
                        <li><span>/</span></li>
                        <li><a href="#">{{ $details?->childcategory->childcategoryName }}</a>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="product">
                    <div class="product-cart">
                        <p style="font-size: 16px;" class="name">{{ $details?->name }} </p>
                        @if($details?->variable_count > 0 && $details?->type == 0)
                        <p class="details-price">
                            @if ($details?->variable->old_price)
                            <del>৳ <span class="old_price">{{ $details?->variable->old_price
                                    }}</span></del>
                            @endif
                            ৳ <span class="new_price">{{ $details?->variable->new_price }}</span>

                            @if ($details?->variable->old_price)
                            <span class="badge bg-success ms-2">
                                Save ৳{{ $details?->variable->old_price -
                                $details?->variable->new_price }}
                            </span>
                            @endif
                        </p>
                        @else
                        <p class="details-price">
                            @if ($details?->old_price)
                            <del>৳{{ $details?->old_price }}</del>
                            @endif
                            ৳{{ $details?->new_price }}

                            @if ($details?->old_price)
                            <span class="badge bg-success ms-2">
                                Save ৳{{ $details?->old_price - $details?->new_price }}
                            </span>
                            @endif
                        </p>
                        @endif

                        <div class="details-ratting-wrapper">
                            @php
                            $averageRating = $reviews->avg('ratting');
                            $filledStars = floor($averageRating);
                            $emptyStars = 5 - $filledStars;
                            @endphp

                            @if ($averageRating >= 0 && $averageRating <= 5) @for ($i=1; $i <=$filledStars; $i++) <i
                                class="fas fa-star"></i>
                                @endfor

                                @if ($averageRating == $filledStars)
                                {{-- If averageRating is an integer, don't display half star --}}
                                @else
                                <i class="far fa-star-half-alt"></i>
                                @endif

                                @for ($i = 1; $i <= 5; $i++) <i class="far fa-star"></i>
                                    @endfor

                                    <span>{{ number_format($averageRating, 2) }}/5</span>
                                    @else
                                    <span>Invalid rating range</span>
                                    @endif
                                    <a class="all-reviews-button" href="#writeReview" data-target="#writeReview"
                                        target="_self">See Reviews</a>
                        </div>

                        <form style="margin-top: 00px;" action="{{ route('cart.store') }}" method="POST"
                            name="formName">
                            @csrf
                            <input type="hidden" name="id" value="{{ $details?->id }}" />
                            @if($productcolors->count() > 0)
                            <div class="pro-color" style="width: 100%;">
                                <div class="color_inner">
                                    <p>Color -</p>
                                    <div class="size-container">
                                        <div class="selector">
                                            @foreach ($productcolors as $key=>$procolor)
                                            <div class="selector-item color-item" data-id="{{$key}}">
                                                <input type="radio" id="fc-option{{ $procolor->color }}"
                                                    value="{{ $procolor->color}}" name="product_color"
                                                    class="selector-item_radio emptyalert stock_color stock_check"
                                                    required data-color="{{ $procolor->color}}" />
                                                <label for="fc-option{{ $procolor->color }}"
                                                    class="selector-item_label">{{
                                                    $procolor->color}}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($productsizes->count() > 0)
                            <div class="pro-size" style="width: 100%;">
                                <div class="size_inner">
                                    <p>Size - <span class="attibute-name"></span></p>
                                    <div class="size-container">
                                        <div class="selector">
                                            @foreach ($productsizes as $prosize)
                                            <div class="selector-item">
                                                <input type="radio" id="f-option{{ $prosize->size }}"
                                                    value="{{ $prosize->size}}" name="product_size"
                                                    class="selector-item_radio emptyalert stock_size stock_check"
                                                    data-size="{{ $prosize->size}}" required />
                                                <label for="f-option{{ $prosize->size }}" class="selector-item_label">{{
                                                    $prosize->size}}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($productmodel->count() > 0)
                            <div class="pro-model w-100 mb-0">
                                <div class="model_inner">
                                    <p>Model - <span class="attibute-name"></span></p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($productmodel as $promodel)
                                        <div class="selector-item p-0">
                                            <input style="font-size:10px;"
                                                class="btn-check selector-item_radio emptyalert stock_model"
                                                type="radio" name="product_model" id="model{{ $loop->index }}"
                                                value="{{ $promodel->model }}" autocomplete="off" required>
                                            <label class="selector-item_label" style="font-size:13px;"
                                                for="model{{ $loop->index }}">
                                                {{ $promodel->model }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif


                            @if($productweight->count() > 0)
                            <div class="pro-weight w-100 mb-3">
                                <div class="weight_inner">
                                    <p class="fw-bold mb-2 text-dark">Weight</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($productweight as $proweight)
                                        <div class="form-check selector-item p-0">
                                            <input class="btn-check stock_weight" type="radio" name="product_weight"
                                                id="weight{{ $loop->index }}" value="{{ $proweight->weight }}"
                                                autocomplete="off" required>
                                            <label class="btn btn-outline-primary px-1 py-1 shadow-sm"
                                                for="weight{{ $loop->index }}">
                                                {{ $proweight->weight }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif




                            @if ($details?->pro_unit)
                            <div class="pro_unig">
                                <label>Unit: {{ $details?->pro_unit }}</label>
                                <input type="hidden" name="pro_unit" value="{{ $details?->pro_unit }}" />
                            </div>
                            @endif
                            <div class="pro_brand mt-2">
                                <p>Brand :
                                    {{ $details?->brand ? $details?->brand->name : 'N/A' }}
                                </p>
                            </div>

                            <div class="row">
                                <div class="qty-cart col-sm-6">
                                    <div class="quantity">
                                        <span class="minus">-</span>
                                        <input type="text" name="qty" value="1" />
                                        <span class="plus">+</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-danger d-flex align-items-center shadow-sm p-2 mt-2 rounded"
                                        role="alert">
                                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                        <span class="">স্টক আউট হওয়ার আগেই অর্ডার
                                            করুন!</span>
                                    </div>
                                </div>

                                <style>
                                    .action-row {
                                        display: flex;
                                        gap: 10px;
                                        margin-top: 0px;
                                        flex-wrap: wrap;
                                        /* keeps layout neat if space is tight */
                                    }

                                    .action-row button {
                                        flex: 1;
                                        padding: 12px 0;
                                        font-size: 15px;
                                        font-weight: 600;
                                        border: none;
                                        border-radius: 8px;
                                        color: #fff;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                    }

                                    /* Add to Cart button */
                                    .add_cart_btn {
                                        background: #28a745;
                                    }

                                    .add_cart_btn:hover {
                                        background: #218838;
                                        transform: translateY(-2px);
                                    }

                                    /* Order Now button */
                                    .order_now_btn {
                                        background: #117DB8;
                                    }

                                    .order_now_btn:hover {
                                        background: #0d6efd;
                                        transform: translateY(-2px);
                                    }

                                    /* Make sure it stays flex even on mobile */
                                    @media (max-width: 576px) {
                                        .action-row {
                                            flex-wrap: nowrap;
                                        }

                                        .action-row button {
                                            font-size: 14px;
                                            padding: 10px 0;
                                        }
                                    }
                                </style>

                                <div class="single_product col-12">
                                    <div class="action-row">
                                        <button type="submit" name="add_cart" onclick="return sendSuccess();"
                                            class="add_cart_btn">
                                            🛒 Add to Cart
                                        </button>

                                        <button type="submit" name="order_now" value="order_now"
                                            onclick="return sendSuccess();" class="order_now_btn">
                                            ⚡ Order Now
                                        </button>
                                    </div>
                                </div>

                            <div class="footer_nav1">
                                <div class="col-12">
                                    <div class="action-row p-1">
                                        <button type="submit" name="add_cart" onclick="return sendSuccess();" class="add_cart_btn">
                                            🛒 Add to Cart
                                        </button>

                                        <button type="submit" name="order_now" value="order_now" onclick="return sendSuccess();"
                                            class="order_now_btn">
                                            ⚡ Order Now
                                        </button>
                                    </div>
                                </div>
                            </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mt-md-2 mt-2">
                                        <h4 class="font-weight-bold">
                                            <a class="btn btn-success w-100 " href="tel: {{ $contact?->hotline }}">
                                                <i class="fa fa-phone-square"></i> Call Now
                                            </a>
                                            <a style="background:#117DB8;"
                                                href="https://wa.me/{{ $contact?->hotline }}?text=Hi%20there!"
                                                target="_blank"
                                                class="btn mt-2 w-100 text-white d-flex align-items-center justify-content-center">
                                                <i class="bi bi-whatsapp fs-4 me-2" aria-hidden="true"></i>
                                                Chat on WhatsApp
                                            </a>
                                        </h4>
                                    </div>

                                    <div class="container my-4">
                                        <div class="row g-3 text-center">
                                            <div class="col-6 col-md-3">
                                                <div class="border rounded shadow-sm p-3 h-100 text-center">
                                                    <i class="fa-solid fa-circle-check fa-2x text-success mb-2"></i>
                                                    <p class="mb-0 fw-semibold">100% Authentic
                                                        Product</p>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="border rounded shadow-sm p-3 h-100 text-center">
                                                    <i class="fa-solid fa-truck-fast fa-2x text-primary mb-2"></i>
                                                    <p class="mb-0 fw-semibold">Fast Delivery</p>
                                                </div>
                                            </div>

                                            <div class="col-6 col-md-3">
                                                <div class="border rounded shadow-sm p-3 h-100 text-center">
                                                    <i class="fa-solid fa-money-bill-wave fa-2x text-warning mb-2"></i>
                                                    <p class="mb-0 fw-semibold">Cash on Delivery</p>
                                                </div>
                                            </div>

                                            <div class="col-6 col-md-3">
                                                <div class="border rounded shadow-sm p-3 h-100 text-center">
                                                    <i class="fa-solid fa-box-open fa-2x text-danger mb-2"></i>
                                                    <p class="mb-0 fw-semibold">Secure Packaging</p>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 mx-auto">
                                                <div class="border rounded shadow-sm p-3 h-100 text-center">
                                                    <i class="fa-solid fa-headset fa-2x text-info mb-2"></i>
                                                    <p class="mb-0 fw-semibold">Live Support</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="description-nav-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="description-nav">
                    <ul class="desc-nav-ul">
                        <li>
                            <a  class="text-dark" href="#description" data-target="#description" class="active">Description</a>
                        </li>
                        <li>
                            <a href="#writeReview" target="_self" data-target="#writeReview">Reviews ({{
                                $reviews->count() }})</a>
                        </li>
                        <li>
                            <a href="#delivery_return" data-target="#delivery_return">Delivery & Return Policy</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Section -->
<section class="pro_details_area">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <!-- Description Section -->
                <div class="description tab-content details-action-box" id="description">
                    <h2 class="text-dark">বিস্তারিত</h2>
                    <p>{!! $details?->description !!}</p>
                </div>

                <!-- Review Section -->
                <div class="tab-content details-action-box" id="writeReview" style="display:none;">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="section-head">
                                    <div class="title">
                                        <h2>Reviews ({{ $reviews->count() }})</h2>
                                        <p>Get specific details about this product from customers who own it.</p>
                                    </div>
                                    <div class="action">
                                        <div>
                                            <button type="button" class="details-action-btn question-btn btn-overlay"
                                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                Write a review
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if ($reviews->count() > 0)
                                <div class="customer-review">
                                    <div class="row">
                                        @foreach ($reviews as $key => $review)
                                        <div class="col-sm-12 col-12">
                                            <div class="review-card">
                                                <p class="reviewer_name"><i data-feather="message-square"></i>
                                                    {{ $review->name }}
                                                </p>
                                                <p class="review_data">{{ $review->created_at->format('d-m-Y') }}</p>
                                                // <p class="review_star">{!! str_repeat('<i
                                                        class="fa-solid fa-star"></i>', $review->ratting) !!}</p>
                                                <p class="review_content">{{ $review->review }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <div class="empty-content">
                                    <i class="fa fa-clipboard-list"></i>
                                    <p class="empty-text">This product has no reviews yet. Be the first one to write a
                                        review.</p>
                                </div>
                                @endif
                                <div class="modal fade" id="exampleModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Your review</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="insert-review">
                                                    @if (Auth::guard('customer')->user())
                                                    <form action="{{ route('customer.review') }}" id="review-form"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{
                                                        $details?->id }}">
                                                        <div class="fz-12 mb-2">
                                                            <div class="rating">
                                                                <label title="Excelent">
                                                                    ☆
                                                                    <input required type="radio" name="ratting"
                                                                        value="5" />
                                                                </label>
                                                                <label title="Best">
                                                                    ☆
                                                                    <input required type="radio" name="ratting"
                                                                        value="4" />
                                                                </label>
                                                                <label title="Better">
                                                                    ☆
                                                                    <input required type="radio" name="ratting"
                                                                        value="3" />
                                                                </label>
                                                                <label title="Very Good">
                                                                    ☆
                                                                    <input required type="radio" name="ratting"
                                                                        value="2" />
                                                                </label>
                                                                <label title="Good">
                                                                    ☆
                                                                    <input required type="radio" name="ratting"
                                                                        value="1" />
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="message-text"
                                                                class="col-form-label">Message:</label>
                                                            <textarea required class="form-control radius-lg"
                                                                name="review" id="message-text"></textarea>
                                                            <span id="validation-message" style="color: red;"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <button class="details-review-button" type="submit">Submit
                                                                Review</button>
                                                        </div>

                                                    </form>
                                                    @else
                                                    <a class="customer-login-redirect"
                                                        href="{{ route('customer.login') }}">Login
                                                        to Post
                                                        Your Review</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery & Return Policy Section -->
                <div class="description tab-content details-action-box" id="delivery_return" style="display:none;">
                    <h2>Delivery & Return Policy</h2>
                    <p>{!! $page?->description !!}</p>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="related-product-section">
    <div class="container">
        <div class="row">
            <div class="related-title">
                <h5>Related Product</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="product-inner owl-carousel related_slider">
                    @foreach ($products as $key => $value)
                    <div class="product_item wist_item">
                        <div class="product_item_inner">
                            @if($value->variable_count > 0 && $value->type == 0)
                            @if($value->variable->old_price)
                            <div class="discount">
                                <p>@php $discount=(((($value->variable->old_price)-($value->variable->new_price))*100) /
                                    ($value->variable->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>

                            </div>
                            @endif
                            @else
                            @if($value->old_price)
                            <div class="discount">
                                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) /
                                    ($value->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
                            </div>
                            @endif
                            @endif
                            <div class="pro_img">
                                <a href="{{ route('product', $value->slug) }}">
                                    <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                        alt="{{ $value->name }}" />
                                </a>
                            </div>
                            <div class="pro_des">
                                <div class="pro_name">
                                    <a href="{{ route('product', $value->slug) }}">{{ $value->name }}</a>
                                </div>


                                <div class="pro_price">
                                    @if($value->variable_count > 0 && $value->type == 0)
                                    <p>
                                        @if ($value->variable->old_price)
                                        <del>৳ {{ $value->variable->old_price }}</del>
                                        @endif

                                        ৳ {{ $value->variable->new_price }}

                                    </p>
                                    @else
                                    <p>
                                        @if ($value->old_price)
                                        <del>৳ {{ $value->old_price }}</del>
                                        @endif

                                        ৳ {{ $value->new_price }}

                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($value->variable_count > 0 && $value->type == 0)
                        <div class="pro_btn">

                            <div class="cart_btn order_button">
                                <a href="{{ route('product', $value->slug) }}" class="addcartbutton">অর্ডার করুন </a>
                            </div>
                        </div>
                        @else
                        <div class="pro_btn">

                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $value->id }}" />
                                <input type="hidden" name="qty" value="1" />
                                <input type="hidden" name="order_now" value="অর্ডার করুন" />
                                <button type="submit">অর্ডার করুন</button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>



@endsection @push('script')
<script src="{{ asset('frontEnd/js/owl.carousel.min.js') }}"></script>

<script src="{{ asset('frontEnd/js/zoomsl.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".details_slider").owlCarousel({
            margin: 15,
            items: 1,
            loop: true,
            dots: false,
            nav: false,
            autoplay: false,
        });
        $(".indicator-item,.color-item").on("click", function() {
            var slideIndex = $(this).data('id');
            $('.details_slider').trigger('to.owl.carousel', slideIndex);
        });
    });
    $(document).ready(function() {
        $('#description').show();
        $('.desc-nav-ul li a, .all-reviews-button').click(function(e) {
            e.preventDefault();
            var target = $(this).data('target');
            $('.tab-content').hide();
            $(target).show();
            $('.desc-nav-ul li a').removeClass('active');
            if ($(this).closest('li').length) {
                $(this).addClass('active');
            }

            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 300);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
  const product = {
    id: "{{ $details?->id }}",
    name: "{{ $details?->name }}",
    brand: "{{ $details?->brand ? $details?->brand->name : 'N/A' }}",
    category: "{{ $details?->category?->name ?? 'N/A' }}",
    price: {{ $details?->variable->new_price ?? 0 }},
    currency: "BDT"
  };

  // Unique event ID for deduplication (Pixel + CAPI)
  const eventId = `viewcontent_${product.id}_{{ session()->getId() }}_${Date.now()}`;

  console.log('👀 ViewContent triggered:', product, 'EventID:', eventId);

  // --- Facebook Pixel (browser-side) ---
  fbq('track', 'ViewContent', {
    content_ids: [product.id],
    content_name: product.name,
    content_category: product.category,
    content_brand: product.brand,
    currency: product.currency,
    value: product.price
  }, {
    eventID: eventId
  });
   // TikTok Pixel tracking
   ttq.track('ViewContent', {
            content_id: product.id,
            content_name: product.name,
            content_type: 'product',
            content_category: product.category,
            content_brand: product.brand,
            value: product.price,
            currency: product.currency
        });
  // --- Facebook Conversion API (server-side) ---
  fetch('{{ route("facebook.view_content_capi") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      event_id: eventId,
      product_id: product.id,
      product_name: product.name,
      brand: product.brand,
      category: product.category,
      value: product.price,
      currency: product.currency,
      client_user_agent: navigator.userAgent,
      client_ip_address: "{{ request()->ip() }}"
    })
  })
  .then(async res => {
    const text = await res.text(); // read raw response
    try {
      const data = JSON.parse(text); // try parse JSON
      console.log('✅ CAPI ViewContent success response:', data);
    } catch (err) {
      console.error('❌ CAPI ViewContent error. Raw response:', text);
    }
  })
  .catch(err => console.error('❌ CAPI ViewContent fetch error:', err));
});
</script>






<script>
    document.addEventListener('DOMContentLoaded', function () {
  const product = {
    id: "{{ $details?->id }}",
    name: "{{ $details?->name }}",
    brand: "{{ $details?->brand ? $details?->brand->name : 'N/A' }}",
    category: "{{ $details?->category?->name ?? 'N/A' }}",
    price: {{ $details?->variable->new_price ?? 0 }},
    currency: "BDT"
  };

  document.querySelectorAll('.add_cart_btn').forEach(button => {
    button.addEventListener('click', function (e) {
      let qtyInput = document.querySelector('input[name="qty"]');
      let quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

      const eventId = `addtocart_${product.id}_{{ session()->getId() }}_${Date.now()}`;

      console.log('🛒 AddToCart triggered:', { ...product, quantity }, 'EventID:', eventId);

      // --- Facebook Pixel ---
      fbq('track', 'AddToCart', {
        content_ids: [product.id],
        content_name: product.name,
        content_category: product.category,
        content_brand: product.brand,
        currency: product.currency,
        value: product.price * quantity,
        contents: [{
          id: product.id,
          quantity: quantity,
          item_price: product.price
        }]
      }, { eventID: eventId });

      // --- TikTok Pixel ---
      if (typeof ttq !== 'undefined') {
        ttq.track('AddToCart', {
          content_id: product.id,
          content_name: product.name,
          content_category: product.category,
          quantity: quantity,
          price: product.price,
          currency: product.currency
        });
        console.log('✅ TikTok AddToCart fired:', { ...product, quantity });
      }

      // --- Facebook CAPI ---
      fetch('{{ route("facebook.add_to_cart_capi") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          event_id: eventId,
          product_id: product.id,
          product_name: product.name,
          brand: product.brand,
          category: product.category,
          value: product.price * quantity,
          currency: product.currency,
          quantity: quantity,
          client_user_agent: navigator.userAgent,
          client_ip_address: "{{ request()->ip() }}"
        })
      })
      .then(async res => {
        const text = await res.text();
        try {
          const data = JSON.parse(text);
          console.log('✅ CAPI AddToCart success:', data);
        } catch (err) {
          console.error('❌ CAPI AddToCart error. Raw response:', text);
        }
      })
      .catch(err => console.error('❌ CAPI AddToCart fetch error:', err));
    });
  });
});
</script>
































<!-- Data Layer End-->
<script>
    $(document).ready(function() {
        $(".related_slider").owlCarousel({
            margin: 10,
            items: 6,
            loop: true,
            dots: true,
            nav: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: true,
                },
                600: {
                    items: 3,
                },
                1000: {
                    items: 6,
                },
            },
        });
        // $('.owl-nav').remove();
    });
</script>
<script>
    $(document).ready(function() {
        $(".minus").click(function() {
            var $input = $(this).parent().find("input");
            var count = parseInt($input.val()) - 1;
            count = count < 1 ? 1 : count;
            $input.val(count);
            $input.change();
            return false;
        });
        $(".plus").click(function() {
            var $input = $(this).parent().find("input");
            $input.val(parseInt($input.val()) + 1);
            $input.change();
            return false;
        });
    });
</script>

<script>
    function sendSuccess() {
        // size validation
        if (document.forms["formName"]["product_size"]) {
            size = document.forms["formName"]["product_size"].value;
            if (size != "") {} else {
                toastr.warning("Please select any size");
                return false;
            }
        }
        if (document.forms["formName"]["product_color"]) {
            color = document.forms["formName"]["product_color"].value;
            if (color != "") {} else {
                toastr.error("Please select any color");
                return false;
            }
        }
    }
</script>
<script>
    $(document).ready(function() {
        $(".rating label").click(function() {
            $(".rating label").removeClass("active");
            $(this).addClass("active");
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".thumb_slider").owlCarousel({
            margin: 15,
            items: 5,
            loop: true,
            dots: false,
            nav: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
        });
    });
</script>

<script type="text/javascript">
    $(".block__pic").imagezoomsl({
        zoomrange: [3, 3]
    });
</script>
<script>
    $(document).on("click", ".stock_check", function () {
        const color = $(".stock_color:checked").data('color');
        const size = $(".stock_size:checked").data('size');
        const model = $(".stock_model:checked").val();  // Optional
        const weight = $(".stock_weight:checked").val(); // Optional
        const id = {{ $details?->id }};

        if (id) {
            $.ajax({
                type: "GET",
                url: "{{ route('stock_check') }}",
                data: {
                    id: id,
                    color: color,
                    size: size,
                    model: model,
                    weight: weight
                },
                dataType: "json",
                success: function (response) {
                    if (response.status) {
                        $(".stock").html('<p><span>Stock : </span>' + response.product.stock + '</p>');
                        $(".old_price").text(response.product.old_price);
                        $(".new_price").text(response.product.new_price);
                        $(".add_cart_btn").prop("disabled", false);
                        $(".order_now_btn").prop("disabled", false);
                    } else {
                        toastr.error('Stock Out', "Please select another option");
                        $(".stock").empty();
                        $(".add_cart_btn").prop("disabled", true);
                        $(".order_now_btn").prop("disabled", true);
                    }
                },
                error: function () {
                    toastr.error('Server Error', 'Something went wrong');
                }
            });
        }
    });
</script>

@endpush