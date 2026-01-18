@extends('frontEnd.layouts.master')
@section('title',$subcategory->meta_title)
@push('css')
<link rel="stylesheet" href="{{asset('frontEnd/css/jquery-ui.css')}}" />
@endpush
@push('seo')
<meta name="app-url" content="{{route('subcategory',$subcategory->slug)}}" />
<meta name="robots" content="index, follow" />
<meta name="description" content="{{ $subcategory->meta_description}}" />
<meta name="keywords" content="{{ $subcategory->slug }}" />

<!-- Twitter Card data -->
<meta name="twitter:card" content="product" />
<meta name="twitter:site" content="{{$subcategory->subcategoryName}}" />
<meta name="twitter:title" content="{{$subcategory->subcategoryName}}" />
<meta name="twitter:description" content="{{ $subcategory->meta_description}}" />
<meta name="twitter:creator" content="gomobd.com" />
<meta property="og:url" content="{{route('subcategory',$subcategory->slug)}}" />
<meta name="twitter:image" content="{{asset($subcategory->image)}}" />

<!-- Open Graph data -->
<meta property="og:title" content="{{$subcategory->subcategoryName}}" />
<meta property="og:type" content="product" />
<meta property="og:url" content="{{route('subcategory',$subcategory->slug)}}" />
<meta property="og:image" content="{{asset($subcategory->image)}}" />
<meta property="og:description" content="{{ $subcategory->meta_description}}" />
<meta property="og:site_name" content="{{$subcategory->subcategoryName}}" />
@endpush
@section('content')
<section class="homeproduct product-section">
    <div class="container">
        <div class="sorting-section">
            <div class="row">
                <div class="col-sm-6">
                    <div class="category-breadcrumb d-flex align-items-center">
                        <a href="{{ route('home') }}">Home</a>
                        <span>/</span>
                        <strong>{{ $subcategory->subcategoryName }}</strong>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="showing-data">
                                <span>Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of
                                    {{ $products->total() }} Results</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="filter_sort">
                                <div class="filter_btn">
                                    <i class="fa fa-list-ul"></i>
                                </div>
                                <div class="page-sort">
                                    <form action="" class="sort-form">
                                        <select name="sort" class="form-control form-select sort">
                                            <option value="1" @if(request()->get('sort')==1)selected @endif>Product: Latest</option>
                                            <option value="2" @if(request()->get('sort')==2)selected @endif>Product: Oldest</option>
                                            <option value="3" @if(request()->get('sort')==3)selected @endif>Price: High To Low</option>
                                            <option value="4" @if(request()->get('sort')==4)selected @endif>Price: Low To High</option>
                                            <option value="5" @if(request()->get('sort')==5)selected @endif>Name: A-Z</option>
                                            <option value="6" @if(request()->get('sort')==6)selected @endif>Name: Z-A</option>
                                        </select>
                                        <input type="hidden" name="min_price" value="{{request()->get('min_price')}}" />
                                        <input type="hidden" name="max_price" value="{{request()->get('max_price')}}" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     <div class="row product_sliders12">
    @foreach ($products as $key => $value)
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <div class="product_item wist_item">
                <div class="product_item_inner">

                    {{-- Discount badge --}}
                    @if($value->variable_count > 0 && $value->type == 0)
                        @if($value->variable->old_price)
                            <div class="sale-badge">
                                <div class="sale-badge-inner">
                                    <div class="sale-badge-box">
                                        <span class="sale-badge-text">
                                            <p>
                                                @php 
                                                    $discount = ((($value->variable->old_price - $value->variable->new_price) * 100) / $value->variable->old_price); 
                                                @endphp
                                                {{ number_format($discount, 0) }}%
                                            </p>
                                            ছাড়
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        @if($value->old_price)
                            <div class="sale-badge">
                                <div class="sale-badge-inner">
                                    <div class="sale-badge-box">
                                        <span class="sale-badge-text">
                                            <p>
                                                @php 
                                                    $discount = ((($value->old_price - $value->new_price) * 100) / $value->old_price); 
                                                @endphp
                                                {{ number_format($discount, 0) }}%
                                            </p>
                                            ছাড়
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    {{-- Product Image --}}
                    <div class="pro_img">
                        <a href="{{ route('product', $value->slug) }}">
                            <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" />
                        </a>
                    </div>

                    {{-- Product Info --}}
                    <div class="pro_des">
                        <div class="pro_name">
                            <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 30) }}</a>
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

                    {{-- Order Button --}}
                    @if($value->variable_count > 0 && $value->type == 0)
                        <div class="pro_btn">
                            <div class="cart_btn order_button">
                                <a href="{{ route('product', $value->slug) }}" class="addcartbutton">অর্ডার করুন</a>
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
            </div>
        </div>
    @endforeach
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


@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script>
        $("#price-range").click(function() {
            $(".price-submit").submit();
        })
        $(".form-attribute").on('change click',function(){
            $(".attribute-submit").submit();
        })
        $(".sort").change(function() {
            $(".sort-form").submit();
        })
        $(".form-checkbox").change(function() {
            $(".subcategory-submit").submit();
        })
    </script>
    <script>
        $(function() {
            $("#price-range").slider({
                step: 5,
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price }},
                values: [
                    {{ request()->get('min_price') ? request()->get('min_price') : $min_price }},
                    {{ request()->get('max_price') ? request()->get('max_price') : $max_price }}
                ],
                slide: function(event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                }
            });
            $("#min_price").val({{ request()->get('min_price') ? request()->get('min_price') : $min_price }});
            $("#max_price").val({{ request()->get('max_price') ? request()->get('max_price') : $max_price }});
            $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values",
                1));

            $("#mobile-price-range").slider({
                step: 5,
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price }},
                values: [
                    {{ request()->get('min_price') ? request()->get('min_price') : $min_price }},
                    {{ request()->get('max_price') ? request()->get('max_price') : $max_price }}
                ],
                slide: function(event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                }
            });
            $("#min_price").val({{ request()->get('min_price') ? request()->get('min_price') : $min_price }});
            $("#max_price").val({{ request()->get('max_price') ? request()->get('max_price') : $max_price }});
            $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values",
                1));

        });
        $(".product_slider12").owlCarousel({
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
    </script>
@endpush