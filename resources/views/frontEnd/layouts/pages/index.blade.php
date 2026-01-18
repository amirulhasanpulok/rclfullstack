@extends('frontEnd.layouts.master') @section('title', 'Top Level Online Ecommerce Market in Bangladesh')
@push('seo')
<meta name="app-url" content="{{ route('home') }}" />
<meta name="robots" content="index, follow" />
<meta name="description" content="{{$generalsetting?->meta_description}}" />
<meta name="keywords" content="{{$generalsetting?->meta_tag}}" />

<!-- Open Graph data -->
<meta property="og:title" content="{{$generalsetting?->meta_title}}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ route('home') }}" />
<meta property="og:image" content="{{ asset($generalsetting?->white_logo) }}" />
<meta property="og:description" content="{{$generalsetting?->meta_description}}" />
@endpush @push('css')
<link rel="stylesheet" href="{{ asset('frontEnd/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('frontEnd/css/owl.theme.default.min.css') }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" rel="stylesheet" />


<style>

    .newArrival {
        background: #F5F5F5;
        border-radius: 10px;
        padding: 10px;
    }

    .view-all-btn {
        background: linear-gradient(135deg, #ffe8e8, #ffffff);
        border: 1px solid #f0dede;
        transition: all 0.3s ease;
    }

    .view-all-btn:hover {
        background: linear-gradient(135deg, #ffd6d6, #fff);
        color: #c0392b;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .section-title-name {
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0.5px;
        transition: 0.3s ease-in-out;
    }

    .section-title-name:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .gradient-text {
        background: linear-gradient(90deg, #ff6a00, #ee0979);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .gradient-text:hover {
        transform: scale(1.05);
    }
</style>
@endpush @section('content')
<section class="slider-section">
    <div class="container" >
        @php
        $firstSlider = $sliders->get(0); 
        $sideImages = $sliders->slice(1, 2); 
        $remainingSliders = $sliders->slice(3);
        @endphp

        <div class="row g-2 align-items-stretch">
            <!-- Left: Main Slider -->
            <div class="col-sm-8 col-12">
                <div class="home-slider-container border rounded shadow-sm overflow-hidden">
                    <div class="main_slider owl-carousel">

                        @if($firstSlider)
                            <div class="slider-item">
                                <a href="{{ $firstSlider->link ?? '#' }}">
                                    <img src="{{ asset($firstSlider->image) }}" alt="Slider Image" class="img-fluid w-100 rounded">
                                </a>
                            </div>
                        @endif

                        @foreach ($remainingSliders as $slider)
                            <div class="slider-item">
                                <a href="{{ $slider->link ?? '#' }}">
                                    <img src="{{ asset($slider->image) }}" alt="Slider Image" class="img-fluid w-100 rounded">
                                </a>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            <!-- Right: Side Images -->
            <div class="col-sm-4 d-none d-sm-flex flex-column justify-content-between">
                @foreach ($sideImages as $img)
                    <div class="mb-2">
                        <a href="{{ $img->link ?? '#' }}">
                            <img src="{{ asset($img->image) }}" alt="Side Image" class="img-fluid rounded shadow-sm w-100">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- slider end -->

<section class="homeproduct" >
    <div class="container">
        <div class="row">
            <div class="liner-continer d-flex align-items-center justify-content-center my-0">
                <div class="flex-grow-1">
                    <hr class="m-0" />
                </div>
                <h4 class="woodmart-title-container title mx-3">
                    Shop by Category
                </h4>
                <div class="flex-grow-1">
                    <hr class="m-0" />
                </div>
            </div>


            <style>
                .cat-img-circle cat_item {
                    width: 100px;
                    height: 100px;
                    object-fit: cover;
                    border-radius: 50%;
                    border: 2px solid #ddd;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                    transition: 0.3s ease;
                }

                .cat-img-circle:hover {
                    transform: scale(1.05);
                }
            </style>
            <div class="col-sm-12">
                <div class="topcategory product_slider-category owl-carousel">
                    @foreach ($frontcategory as $key => $value)
                 
                    <div class="cat_item text-center" style="max-width: 80px; margin: 5px auto;">
                        <div class="cat_img mb-1 shadow-sm">
                            <a href="{{ route('category', $value->slug) }}">
                                <img src="{{ asset($value?->icon) }}" alt="{{ $value?->name }}" class="rounded-circle" />
                            </a>
                        </div>
                        <div class="cat_name">
                            <a href="{{ route('category', $value?->slug) }}" class="text-dark text-decoration-none fw-medium">
                                {{ $value->name }}
                            </a>
                        </div>
                    </div>

                    @endforeach

                </div>
            </div>

        </div>
    </div>
</section>



<!-- new Arrival -->


<section class="homeproduct">
    <div class="container">
        <div class="row newArrival">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="text-center my-3">
                                <span class="section-title-name fw-semibold fs-5 px-3 py-1 rounded bg-light border-start border-4 border-info d-inline-block shadow-sm">
                                    <span class="text-info fw-bold">New</span> Arrival
                                </span>
                            </div>


                            <div class="text-center mt-2">
                                <a class="btn px-4 py-2 rounded-pill shadow-sm fw-semibold text-dark view-all-btn" href="#">
                                    VIEW ALL <i class="fa-solid fa-angles-right ms-1"></i>
                                </a>
                            </div>

                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($newArrival as $key => $value)
                    <div class="product_item wist_item">
                        <div class="product_item_inner">
                            @if($value->variable_count > 0 && $value->type == 0)
                            @if($value->variable->old_price)
                            <div class="discount">
                                <p>@php $discount=(((($value->variable->old_price)-($value->variable->new_price))*100) / ($value->variable->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>

                            </div>
                            @endif
                            @else
                            @if($value->old_price)
                            <div class="discount">
                                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
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
                                <div class="pro_name mb-0 m-0">
                                    <a
                                        href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 35) }}</a>
                                </div>
                                <div class="pro_price mt-0">
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
                                <a style="background: #1a81b7;" href="{{ route('product', $value->slug) }}"
                                    class="addcartbutton">অর্ডার করুন </a>
                            </div>
                        </div>
                        @else
                        <div class="pro_btn">

                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $value->id }}" />
                                <input type="hidden" name="qty" value="1" />
                                <input type="hidden" name="order_now" value="অর্ডার করুন" />
                                <button style="background: #1a81b7;" type="submit">অর্ডার করুন</button>
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




<style>
    .equal-height {
        height: 100%;
        object-fit: cover;
    }

    .img-height-responsive {
        height: 100%;
    }

    @media (min-width: 768px) {
        .img-height-responsive {
            height: 85% !important;
        }
    }

    .order-now-btn {
        display: inline-block;
        background: #1a81b7;
        color: #fff;
        padding: 5px;
        border: none;
        width: 100%;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 5px 15px rgba(248, 87, 166, 0.3);
    }

    .order-now-btn:hover {
        background: linear-gradient(135deg, #ff5858, #f857a6);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px #0b87caff;
    }
</style>














<!-- New Arrival -->

<section class="homeproduct">
    <div class="container">
        <div class="row newArrival">
            <div class="col-sm-12">
                <div class="sec_title">
                    <h3 class="section-title-header">
                        <div class="timer_inner">
                            <div class="text-center my-3">
                                <span class="section-title-name fw-bold fs-4 gradient-text">
                                    Super Deals
                                </span>
                            </div>


                            <div class="">
                                <div class="offer_timer" id="simple_timer"></div>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="product_slider owl-carousel">
                    @foreach ($hotdeal_top as $key => $value)
                    <div class="product_item wist_item">
                        <div class="product_item_inner">
                            @if($value->variable_count > 0 && $value->type == 0)
                            @if($value->variable->old_price)
                            <div class="discount">
                                <p>@php $discount=(((($value->variable->old_price)-($value->variable->new_price))*100) / ($value->variable->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>

                            </div>
                            @endif
                            @else
                            @if($value->old_price)
                            <div class="discount">
                                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
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
                                <div class="pro_name mb-0 m-0">
                                    <a
                                        href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 35) }}</a>
                                </div>
                                <div class="pro_price mt-0">
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
                                <a style="background: #1a81b7;" href="{{ route('product', $value->slug) }}"
                                    class="addcartbutton">অর্ডার করুন </a>
                            </div>
                        </div>
                        @else
                        <div class="pro_btn">

                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $value->id }}" />
                                <input type="hidden" name="qty" value="1" />
                                <input type="hidden" name="order_now" value="অর্ডার করুন" />
                                <button style="background: #1a81b7;" type="submit">অর্ডার করুন</button>
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

@foreach ($homecategory as $homecat)

<section class="homeproduct">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="title-inner d-flex justify-content-between align-items-center flex-wrap">
                    <div class="section-title">
                        <h2>{{ $homecat->name }}</h2>
                    </div>
                    <div class="section-btn">
                        <a style="background:#117DB8;" href="{{ route('category', $homecat->slug) }}">View More</a>
                    </div>
                </div>
            </div>

            @php
            $products = $homecat->products;
            $isOdd = $loop->index % 2 !== 0;
            @endphp
        </div>

        <div class="row align-items-stretch">
            @if($homecat->banner_image==1)
                <div class="col-md-3 col-sm-12 mb-3 mb-md-0 d-flex {{ $isOdd ? 'order-md-2' : 'order-md-1' }}">
                    <img class="w-100 rounded img-height-responsive" src="{{ $homecat->image }}" alt="">
                </div>
            @endif

            <!-- Product Slider Column -->
            <div class="  @if($homecat->banner_image==1) col-md-9 @endif @if($homecat->banner_image==0) col-md-12 @endif  col-sm-12 mt-3 mt-md-0 d-flex flex-column {{ $isOdd ? 'order-md-1' : 'order-md-2' }}">
                <div class=" @if($homecat->banner_image==1) hotdeals-slider111 @endif @if($homecat->banner_image==0) hotdeals-slider1 @endif  owl-carousel h-100">
                    @foreach($products as $key => $value)
                    <div class="product_item wist_item">
                        <div class="product_item_inner">
                            @if($value->variable_count > 0 && $value->type == 0)
                            @if($value->variable->old_price)
                            <div class="discount">
                                <p>@php $discount=(((($value->variable->old_price)-($value->variable->new_price))*100) / ($value->variable->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>

                            </div>
                            @endif
                            @else
                            @if($value->old_price)
                            <div class="discount">
                                <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp -{{ number_format($discount, 0) }}%</p>
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
                                <div class="pro_name mb-0 m-0">
                                    <a
                                        href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 45) }}</a>
                                </div>
                                <div class="pro_price mt-0">
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
                                <a style="background: #1a81b7;" href="{{ route('product', $value->slug) }}"
                                    class="addcartbutton">অর্ডার করুন </a>
                            </div>
                        </div>
                        @else
                        <div class="pro_btn">

                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $value->id }}" />
                                <input type="hidden" name="qty" value="1" />
                                <input type="hidden" name="order_now" value="অর্ডার করুন" />
                                <button style="background: #1a81b7;" type="submit">অর্ডার করুন</button>
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
@endforeach


@foreach($featured as $item)
        <section class="homeproduct">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                <div class="title-inner d-flex justify-content-between align-items-center flex-wrap">
                    <div class="section-title">
                        <h2>{{ $item->name }}</h2>
                    </div>
                    <div class="section-btn">
                        <a style="background:#117DB8;"  href="{{ route('category', $item->slug) }}">View More</a>
                    </div>
                </div>
            </div>
                </div>

                <div class="row newArrival">
                @if($homecat->banner_image==1)
                    <div class="col-md-3 col-sm-12 mb-3">
                        <img class="w-100 rounded h-100" src="{{ asset($item->image ?? 'default.jpg') }}" alt="{{ $item->title }}">
                    </div>
                @endif
                    <div class="@if($homecat->banner_image==1) col-md-9 @endif @if($homecat->banner_image==0) col-md-12 @endif  col-sm-12">
                        {{-- TAB HEADERS --}}
                        <ul class="nav nav-pills mb-3" id="pills-tab-{{ $loop->index }}" role="tablist">
                            @foreach($item->subcategories_home as $index => $subcategory)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $subcategory->id }}" data-bs-toggle="pill" data-bs-target="#content-{{ $subcategory->id }}" type="button" role="tab">
                                        {{ $subcategory->subcategoryName }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        {{-- TAB CONTENTS --}}
                        <div class="tab-content" id="pills-tabContent-{{ $loop->index }}">
                            @foreach($item->subcategories_home as $index => $subcategory)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $subcategory->id }}" role="tabpanel">
                                    <div class="@if($homecat->banner_image==1) hotdeals-slider111 @endif @if($homecat->banner_image==0) hotdeals-slider1 @endif  owl-carousel">
                                        @foreach($subcategory->products ?? [] as $product)
                                            <div class="product_item wist_item">
                                                <div class="product_item_inner">
                                                    {{-- DISCOUNT --}}
                                                    @php
                                                        $old = $product->variable->old_price ?? $product->old_price;
                                                        $new = $product->variable->new_price ?? $product->new_price;
                                                    @endphp
                                                    @if($old && $new)
                                                        <div class="discount">
                                                            <p>-{{ number_format((($old - $new) * 100) / $old, 0) }}%</p>
                                                        </div>
                                                    @endif

                                                    {{-- IMAGE --}}
                                                    <div class="pro_img">
                                                        <a href="{{ route('product', $product->slug) }}">
                                                            <img src="{{ asset($product->image->image ?? 'no-image.jpg') }}" alt="{{ $product->name }}" />
                                                        </a>
                                                    </div>

                                                    {{-- DETAILS --}}
                                                    <div class="pro_des">
                                                        <div class="pro_name">
                                                            <a href="{{ route('product', $product->slug) }}">
                                                                {{ Str::limit($product->name, 40) }}
                                                            </a>
                                                        </div>
                                                        <div class="pro_price">
                                                            @if($old)
                                                                <del>৳ {{ $old }}</del>
                                                            @endif
                                                            ৳ {{ $new }}
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- BUTTON --}}
                                                <div class="pro_btn">
                                                    <div class="cart_btn order_button">
                                                        <a style="background: #1a81b7;" href="{{ route('product', $product->slug) }}"
                                                            class="addcartbutton">অর্ডার করুন </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach









@endsection @push('script')
<script src="{{ asset('frontEnd/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontEnd/js/jquery.syotimer.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".main_slider").owlCarousel({
            items: 1,
            loop: true,
            dots: false,
            autoplay: true,
            nav: false,
            autoplayHoverPause: false,
            margin: 0,
            mouseDrag: true,
            smartSpeed: 1000,
            autoplayTimeout: 4000

        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".hotdeals-slider").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 3,
                    nav: true,
                },
                600: {
                    items: 3,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: true,
                    loop: false,
                },
            },
        });
    });
    $(document).ready(function() {
        $(".hotdeals-slider1").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
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
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: true,
                    loop: false,
                },
            },
        });
          $(".hotdeals-slider111").owlCarousel({
            margin: 15,
            loop: true,
            dots: false,
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
                    nav: false,
                },
                1000: {
                    items: 4,
                    nav: true,
                    loop: false,
                },
            },
        });
    });
</script>

<script>
    $(document).ready(function() {


        $(".product_slider").owlCarousel({
            margin: 15,
            items: 6,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                600: {
                    items: 4,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: false,
                },
            },
        });
        $(".product_slider2").owlCarousel({
            margin: 15,
            items: 6,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                600: {
                    items: 4,
                    nav: false,
                },
                1000: {
                    items: 4,
                    nav: false,
                },
            },
        });
        $(".product_sliders3").owlCarousel({
            margin: 15,
            items: 6,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                },
                600: {
                    items: 4,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: false,
                },
            },
        });

        $(".product_slider-category").owlCarousel({
            margin: 15,
            items: 6,
            loop: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 4,
                    nav: false,
                },
                600: {
                    items: 6,
                    nav: false,
                },
                1000: {
                    items: 8,
                    nav: false,
                },
            },
        });

    });
</script>
<script>
    $("#simple_timer").syotimer({
        date: new Date(2015, 0, 1),
        layout: "hms",
        doubleNumbers: false,
        effectType: "opacity",

        periodUnit: "d",
        periodic: true,
        periodInterval: 1,
    });
</script>
@endpush