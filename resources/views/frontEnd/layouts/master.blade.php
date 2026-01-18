<!DOCTYPE html>
<html lang="en">

<head>
<!-- Meta Pixel Code -->



    <meta name="description" content="Buy exclusive genuine accessories in Bangladesh. Shop smart watches, stylish phone covers, high-quality headphones, durable powerbanks, premium screen protectors, and camera protectors. Fast delivery all over BD.">
    <meta name="keywords" content="Accessories Store BD, Smart Watch Bangladesh, Phone Covers BD, Headphones BD, Powerbanks Bangladesh, Screen Protector BD, Camera Protector BD, Genuine Mobile Accessories Bangladesh">
   
    <meta property="og:type" content="website">
    <meta property="og:title" content="Exclusive Genuine Accessories Store in BD">
    <meta property="og:description" content="Shop genuine mobile & tech accessories in Bangladesh. Smart watches, phone covers, headphones, powerbanks, screen & camera protectors at best prices.">
    <meta property="og:url" content="{{route('home')}}">
    <meta property="og:image" content="{{asset('uploads/logo.png')}}"> <!-- Replace with your logo or banner -->
    <meta property="og:site_name" content="{{$generalsetting?->name}}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Exclusive Genuine Accessories Store in BD">
    <meta name="twitter:description" content="Discover genuine accessories in Bangladesh: smart watches, phone covers, headphones, powerbanks, screen protectors, camera protectors.">
    <meta name="twitter:image" content="{{asset('uploads/logo.png')}}">
    <!--<meta name="google-site-verification" content="w-8P-z3yDwtpLjhc5Mhp0QfDYz8xxSSTRJQmcLGPQXg" />-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- TikTok Pixel Code Start -->
    <script>
    !function (w, d, t) {
    w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
    var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
    ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};


    ttq.load('D3DVM0JC77U2RE92NOC0');
    ttq.page();
    }(window, document, 'ttq');
    </script>
    <!-- TikTok Pixel Code End -->

    <title>{{$generalsetting?->name}}</title>
    
    <link rel="shortcut icon" href="{{asset($generalsetting?->favicon)}}" alt="{{$generalsetting?->name}}" />
    <meta name="author" content="{{$generalsetting?->name}}" />
    <link rel="canonical" href="{{route('home')}}" />
    @stack('seo')
    @stack('css')
    <link rel="stylesheet" href="{{asset('frontEnd/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/animate.css')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/woodmart-font.css')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/all.min.css')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/owl.carousel.min.css')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/owl.theme.default.min.css')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/mobile-menu.css')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/select2.min.css')}}" />
    <!-- toastr css -->
    <link rel="stylesheet" href="{{asset('backEnd/')}}/assets/css/toastr.min.css" />

    <link rel="stylesheet" href="{{asset('frontEnd/css/wsit-menu.css')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/style.css?v=1.0.22')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/responsive.css?v=1.0.22')}}" />
    <link rel="stylesheet" href="{{asset('frontEnd/css/main.css')}}" />


    <style>
        .side_cat_img {
            width: 24px;
            height: 24px;
            object-fit: cover;
            border-radius: 4px;
        }

        .first-nav .list-group-item {
            border: none;
            border-bottom: 1px solid #eee;
            font-size: 15px;
            padding: 10px 15px;
        }

        .menu-category-toggle,
        .menu-subcategory-toggle {
            cursor: pointer;
        }

        .second-nav,
        .third-nav {
            padding-left: 10px;
        }

        .third-nav .list-group-item {
            font-size: 14px;
        }

        .menu-childcategory-name,
        .menu-subcategory-name,
        .menu-category-name {
            text-decoration: none;
            color: #333;
        }

        .menu-childcategory-name:hover,
        .menu-subcategory-name:hover,
        .menu-category-name:hover {
            color: #ffffffff;
        }
    </style>


    

    <style>
        .desc-nav-ul li a.active {
            background-color: #ffffffff;
            color: #fff;
        }
    </style>

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){
  if(f.fbq) return;
  n=f.fbq=function(){
    n.callMethod ?
    n.callMethod.apply(n,arguments) : n.queue.push(arguments)
  };
  if(!f._fbq) f._fbq=n;
  n.push=n; n.loaded=!0; n.version='2.0'; n.queue=[];
  t=b.createElement(e); t.async=!0; t.src=v;
  s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)
}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');

// Initialize Pixel
fbq('init', '1048739210676271');

// Track PageView
fbq('track', 'PageView');

// Log event to console
console.log('✅ Facebook Pixel PageView fired');
</script>

<noscript>
<img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1048739210676271&ev=PageView&noscript=1"
/>
</noscript>

</head>

<body class="gotop" style="background: #FFFFFF;">



    @php
    $subtotal = Cart::instance('shopping')->subtotal();
    @endphp
    <div class="mobile-menu">
        <div class="mobile-menu-logo">
            <div class="logo-image">
                <img src="{{asset($generalsetting?->white_logo)}}" alt="" />
            </div>
            <div class="mobile-menu-close">
                <i class="fa fa-times"></i>
            </div>
        </div>
        <ul class="first-nav">
            @foreach($menucategories as $scategory)
            <li class="parent-category">
                <a href="{{url('category/'.$scategory?->slug)}}" class="menu-category-name">
                    <img src="{{asset($scategory->image)}}" alt="" class="side_cat_img" />
                    {{ $scategory->name }}
                </a>
                @if($scategory?->subcategories->count() > 0)
                <span class="menu-category-toggle">
                    <i class="fa fa-chevron-down"></i>
                </span>
                @endif
                <ul class="second-nav" style="display: none;">
                    @foreach($scategory?->subcategories as $subcategory)
                    <li class="parent-subcategory">
                        <a href="{{url('subcategory/'.$subcategory?->slug)}}"
                            class="menu-subcategory-name">{{$subcategory->subcategoryName}}</a>
                        @if($subcategory?->childcategories->count() > 0)
                        <span class="menu-subcategory-toggle"><i class="fa fa-chevron-down"></i></span>
                        @endif
                        <ul class="third-nav" style="display: none;">
                            @foreach($subcategory?->childcategories as $childcat)
                            <li class="childcategory"><a href="{{url('products/'.$childcat->slug)}}" class="menu-childcategory-name">{{$childcat->childcategoryName}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </div>
    <header id="navbar_top">
        <div class="mobile-header navbar navbar-light bg-light fixed-top shadow-sm" style="background:#f0f0f0; ">
            <div class="mobile-logo">
                <div class="menu-bar">
                    <a style="background:#117DB8;" class="toggle" >
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </div>
                <div class="menu-logo">
                    <a href="{{route('home')}}"><img src="{{asset($generalsetting?->white_logo)}}" alt="" /></a>
                </div>
                <div class="menu-bag">
                    <a style="background:#117DB8;" href="{{route('customer.checkout')}}" class="margin-shopping">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="mobilecart-qty">{{Cart::instance('shopping')->count()}}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="mobile-search" style="margin-top: 85px;">
            <form id="searchForm" action="{{route('search')}}">
                <input type="text" placeholder="Search Product ... " value="" class="msearch_keyword msearch_click src" name="keyword" />
                <button style="background:#117DB8;"><i data-feather="search"></i></button>
            </form>
            <div class="search_result"></div>
        </div>

        <div class="main-header">

            <!-- Top Header Start -->
            <!-- <div class="top-header" style="background: #191919; color: white; font-size: 12px; padding: 5px 0; position: relative; overflow: hidden; height: 25px;">
                <div class="container d-flex justify-content-between align-items-center">
                    <div class="scrolling-text-wrapper" style="height: 25px; overflow: hidden;">
                        <div class="scrolling-text" style="animation: scrollUp 5s linear infinite;">
                            <div>🔥 Hot Deal: Get 20% off on all items! 🔥</div>
                            <div>📞 24/7 Support: <a href="tel:{{ $contact?->hotline }}" style="color: white;
                            text-decoration: underline;">{{ $contact?->hotline }}</a></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Top Header End -->

            <!-- Add this CSS inside <style> -->
            <style>
                @keyframes scrollUp {
                    0% {
                        transform: translateY(0);
                    }

                    50% {
                        transform: translateY(-25px);
                    }

                    100% {
                        transform: translateY(0);
                    }
                }

                .scrolling-text>div {
                    height: 25px;
                }

                .wd-tools-icon::before {
                    content: '\2630';
                    /* Example: hamburger icon (☰) */
                    font-size: 26px;
                    margin-right: 8px;
                    display: inline-block;
                }
            </style>

            <div class="logo-area">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="logo-header" >
                                <div class="main-logo">
                                    <a href="{{route('home')}}"><img src="{{asset($generalsetting?->white_logo)}}"
                                            alt="" /></a>
                                </div>
                                <div class="main-search">
                                    <form id="MainSearch" action="{{route('search')}}">
                                        <input type="text" placeholder="Search Product..." class="search_keyword search_click mainsrc" name="keyword" />
                                        <button style="background:#117DB8;">
                                            <i data-feather="search"></i>
                                        </button>
                                    </form>
                                    <div class="search_result"></div>
                                </div>
                                <div class="header-list-items">
                                    <div class="helpline-wrapper">

                                        <div class="header-action-menu">
                                            <div class="action-item">
                                                <a style="background:#117DB8;" href="{{route('customer.order_track')}}">
                                                    <img class="track-image" src="{{ asset('frontEnd/images/truck-icon.png') }}">
                                                </a>
                                            </div>

                                            @if(Auth::guard('customer')->user())
                                            <div class="action-item">
                                                <a style="background:#117DB8;" href="{{route('customer.account')}}" title="{{Str::limit(Auth::guard('customer')->user()->name,14)}}">
                                                    <i class="woodmart woodmart-user"></i>
                                                </a>
                                            </div>
                                            @else
                                            <div class="action-item">
                                                <a style="background:#117DB8;" href="{{route('customer.login')}}" title="Login / Sign Up">
                                                    <i class="woodmart woodmart-user"></i>
                                                </a>
                                            </div>
                                            @endif

                                            <div class="action-item" id="cart-qty">
                                                <a href="" style="background:#117DB8;">
                                                    <i class="woodmart woodmart-cart"></i>
                                                    <span>{{Cart::instance('shopping')->count()}}</span>
                                                </a>

                                                <div class="cshort-summary">
                                                    <ul>
                                                        @foreach(Cart::instance('shopping')->content() as $key=>$value)
                                                        <li>
                                                            <a href=""><img src="{{asset($value->options->image)}}" alt="" /></a>
                                                        </li>
                                                        <li><a href="">{{Str::limit($value->name, 30)}}</a></li>
                                                        <li>Qty: {{$value->qty}}</li>
                                                        <li>
                                                            <p>৳{{$value->price}}</p>
                                                            <button class="remove-cart cart_remove" data-id="{{$value->rowId}}"><i class="fa-regular fa-trash-can trash_icon" title="Delete this item"></i></button>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    <p><strong>সর্বমোট : ৳{{$subtotal}}</strong></p>
                                                    <a href="{{route('customer.checkout')}}" class="go_cart"> অর্ডার করুন </a>


                                                </div>
                                            </div>
                                            <div class="action-item1">
                                                <a class="btn " style="color: dark; font-size:25px;" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                                                    <span class="wd-tools-icon"></span>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .white-text {
                    color: #FFFFFF !important;
                }
            </style>
            <div class="menu-area"  style="background:#048ed8; !important; padding-top:3px;">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-between">
                            <div class="catagory_menu ">
                                <ul>
                                    <div class="offcanvas offcanvas-end shadow-sm" tabindex="-1" id="sidebar" style="width: 280px;">
                                        <div class="offcanvas-header py-2 px-3 border-bottom">
                                            <h6 class="offcanvas-title mb-0 text-uppercase">Categories</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>

                                        <div class="offcanvas-body p-0 bg-light">
                                            <ul class="list-group rounded-0">
                                                @foreach($menucategories as $scategory)
                                                <li class="list-group-item py-2 px-3 bg-white border-0 border-bottom">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <a href="{{ url('category/'.$scategory->slug) }}" class="text-decoration-none text-light d-flex align-items-center small fw-semibold">
                                                            <img src="{{ asset($scategory->image) }}" alt="" class="me-2" width="20" height="20" />
                                                            {{ $scategory->name }}
                                                        </a>
                                                        @if($scategory->subcategories->count() > 0)
                                                        <span class="text-muted" role="button" onclick="toggleSubmenu(this)">
                                                            <i class="fa fa-chevron-down small"></i>
                                                        </span>
                                                        @endif
                                                    </div>

                                                    @if($scategory->subcategories->count() > 0)
                                                    <ul class="list-group list-group-flush ms-3 mt-2 bg-white rounded" style="display: none;">
                                                        @foreach($scategory->subcategories as $subcategory)
                                                        <li class="list-group-item py-1 px-2 border-0">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <a href="{{ url('subcategory/'.$subcategory->slug) }}" class="text-decoration-none text-dark small">
                                                                    {{ $subcategory->subcategoryName }}
                                                                </a>
                                                                @if($subcategory->childcategories->count() > 0)
                                                                <span class="text-muted" role="button" onclick="toggleSubmenu(this)">
                                                                    <i class="fa fa-chevron-down small"></i>
                                                                </span>
                                                                @endif
                                                            </div>

                                                            @if($subcategory->childcategories->count() > 0)
                                                            <ul class="list-group list-group-flush ms-3 mt-2 bg-white" style="display: none;">
                                                                @foreach($subcategory->childcategories as $childcat)
                                                                <li class="list-group-item py-1 ps-3 pe-2 border-0">
                                                                    <a href="{{ url('products/'.$childcat->slug) }}" class="text-decoration-none text-secondary small">
                                                                        {{ $childcat->childcategoryName }}
                                                                    </a>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                            @endif
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>





                                    <script>
                                        function toggleSubmenu(el) {
                                            const submenu = el.parentElement.nextElementSibling;
                                            if (submenu && submenu.style.display === "none") {
                                                submenu.style.display = "block";
                                                el.querySelector('i').classList.add("rotate");
                                            } else if (submenu) {
                                                submenu.style.display = "none";
                                                el.querySelector('i').classList.remove("rotate");
                                            }
                                        }
                                    </script>











                                    {{-- end offcanvas --}}
                                    <li class="cat_bar text-white">
                                        <a class="text-white" href="{{route('home')}}" style="margin-top: 0px;"> <i class="fas fa-home" style="font-size: large;"></i> Home </a>
                                    </li>

                                    @foreach ($menucategories as $scategory)
                                    <li class="cat_bar">
                                        <a class="text-white" style="margin-top: 4px;" href="{{ url('category/' . $scategory->slug) }}">
                                            <span class="cat_head">{{ $scategory->name }}</span>
                                            @if ($scategory->subcategories->count() > 0)
                                            <i class="fa-solid fa-angle-down cat_down"></i>
                                            @endif
                                        </a>
                                        @if($scategory->subcategories->count() > 0)
                                        <ul class="Cat_menu">
                                            @foreach ($scategory->subcategories as $subcat)
                                            <li class="Cat_list cat_list_hover">
                                                <a class="text-dark" href="{{ url('subcategory/' . $subcat->slug) }}">
                                                    <span>{{ Str::limit($subcat->subcategoryName, 25) }}</span>
                                                    @if($subcat->childcategories->count() > 0)
                                                    <i class="fa-solid fa-chevron-right cat_down"></i>
                                                    @endif
                                                </a>
                                                @if($subcat->childcategories->count() > 0)
                                                <ul class="child_menu">
                                                    @foreach($subcat->childcategories as $childcat)
                                                    <li class="child_main">
                                                        <a class="text-dark" href="{{ url('products/'.$childcat->slug) }}">
                                                            {{ $childcat->childcategoryName }}
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>



                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main-header end -->
    </header>
    <div id="content">
        @yield('content')
    </div>
    <!-- content end -->
    <footer>
        <div class="footer-top bg-dark text-light py-5">
        <div class="container">
            <div class="row gy-5">

            <!-- Column 1: Logo & Description -->
            <div class="col-md-3">
                <!-- Desktop -->
                <div class="d-none d-md-block text-md-start text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset($generalsetting?->white_logo) }}" alt="Logo" class="img-fluid mb-3" style="max-height:60px;" />
                </a>
               <h3 class="small text-white"> {!! $generalsetting?->description !!}</h3>


                </div>

                <!-- Mobile Accordion -->
                <div class="accordion d-md-none" id="footerAccordion1">
                <div class="accordion-item bg-dark border-0">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-light py-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse1">
                        About
                    </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#footerAccordion1">
                    <div class="accordion-body py-2">
                        <a href="{{ route('home') }}">
                        <img src="{{ asset($generalsetting?->white_logo) }}" alt="Logo" class="img-fluid mb-2" style="max-height:50px;" />
                        </a>
                        <h4 class="small opacity-75 mb-0 text-white">{!! $generalsetting?->description !!}</h4>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Column 2: Contact -->
            <div class="col-md-3">
                <!-- Desktop -->
                <div class="d-none d-md-block">
                <h5 class="fw-bold mb-3 text-uppercase">Contact Us</h5>
               <p class="mb-2">
                    <a href="#" class="link-light text-light text-decoration-none d-inline-flex align-items-center">
                        <i class="fa-solid fa-map me-2 text-light"></i> Motalib Plaza, Hatirpool, Dhaka
                    </a>
                </p>


                <p class="mb-2"><i class="fa-solid fa-headphones me-2 text-primary"></i>
                    <a href="tel:{{ $contact?->hotline }}" class="text-decoration-none text-light hover-link text-white">{{ $contact?->hotline }}</a>
                </p>
                <p><i class="fa-solid fa-envelope me-2 text-primary"></i>
                    <a href="mailto:{{ $contact?->hotmail }}" class="text-decoration-none text-light hover-link">{{ $contact?->hotmail }}</a>
                </p>
                </div>

                <!-- Mobile Accordion -->
                <div class="accordion d-md-none" id="footerAccordion2">
                <div class="accordion-item bg-dark border-0">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-light py-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse2">
                        Contact Us
                    </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#footerAccordion2">
                    <div class="accordion-body py-2">
                        <p class="mb-2 text-white"><i class="fa-solid fa-map me-2 text-light"></i>Motalib Plaza, Hatirpool, Dhaka</p>
                        <p class="mb-2"><i class="fa-solid fa-headphones me-2 text-primary"></i>
                        <a href="tel:{{ $contact?->hotline }}" class="text-decoration-none text-light hover-link">{{ $contact?->hotline }}</a>
                        </p>
                        <p class="mb-0"><i class="fa-solid fa-envelope me-2 text-primary"></i>
                        <a href="mailto:{{ $contact?->hotmail }}" class="text-decoration-none text-light hover-link">{{ $contact?->hotmail }}</a>
                        </p>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Column 3: Useful Links -->
            <div class="col-md-3">
                <!-- Desktop -->
                <div class="d-none d-md-block">
                <h5 class="fw-bold mb-3 text-uppercase">Useful Links</h5>
                <div class="useful-links">
                    <p class="mb-2"><a href="{{ route('contact') }}" class="text-decoration-none text-light hover-link">Contact Us</a></p>
                    @foreach($cmnmenu as $page)
                    <p class="mb-2"><a href="{{ route('page',['slug'=>$page->slug]) }}" class="text-decoration-none text-light hover-link">{{ $page->name }}</a></p>
                    @endforeach
                </div>
                </div>

                <!-- Mobile Accordion -->
                <div class="accordion d-md-none" id="footerAccordion3">
                <div class="accordion-item bg-dark border-0">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-light py-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse3">
                        Useful Links
                    </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#footerAccordion3">
                    <div class="accordion-body py-2">
                        <p class="mb-2"><a href="{{ route('contact') }}" class="text-decoration-none text-light hover-link">Contact Us</a></p>
                        @foreach($cmnmenu as $page)
                        <p class="mb-2"><a href="{{ route('page',['slug'=>$page->slug]) }}" class="text-decoration-none text-light hover-link">{{ $page->name }}</a></p>
                        @endforeach
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Column 4: Social -->
            <div class="col-md-3">
                <!-- Desktop -->
                <div class="d-none d-md-block">
                <h5 class="fw-bold mb-3 text-uppercase">Stay Connected</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($socialicons as $value)
                    <a href="{{ $value->link }}" target="_blank"
                        class="d-flex align-items-center justify-content-center rounded-circle text-white social-icon"
                        style="background: {{ $value->color }}; width: 42px; height: 42px;">
                        <i class="{{ $value->icon }}"></i>
                    </a>
                    @endforeach
                </div>
                </div>

                <!-- Mobile Accordion -->
                <div class="accordion d-md-none" id="footerAccordion4">
                <div class="accordion-item bg-dark border-0">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-light py-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse4">
                        Stay Connected
                    </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#footerAccordion4">
                    <div class="accordion-body d-flex flex-wrap gap-2 py-2">
                        @foreach($socialicons as $value)
                        <a href="{{ $value->link }}" target="_blank"
                            class="d-flex align-items-center justify-content-center rounded-circle text-white social-icon"
                            style="background: {{ $value->color }}; width: 42px; height: 42px;">
                            <i class="{{ $value->icon }}"></i>
                        </a>
                        @endforeach
                    </div>
                    </div>
                </div>
                </div>
            </div>

            </div> 
        </div>
        </div>

        <style>
            .hover-link:hover {
                color: #0d6efd !important; /* Bootstrap primary */
                padding-left: 4px;
                transition: all 0.3s ease;
            }
            .social-icon {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .social-icon:hover {
                transform: scale(1.1);
                box-shadow: 0 4px 10px rgba(255,255,255,0.3);
            }
            .accordion-button::after {
                filter: invert(1); /* white caret */
            }
        </style>




        <div class="footer-bottom py-3" style="background: linear-gradient(90deg, #147ab6, #0f5e8d);">
            <div class="container">
                <div class="row align-items-center gy-3">
                    <!-- Left: Copyright -->
                    <div class="col-md-9 text-center text-md-start">
                        <p class="mb-0 text-white">
                            © <span class="text-warning">Nicola Tech</span> — All rights reserved.
                            <br class="d-md-none">
                            Developed with <span class="text-danger"> ❤️ </span> by
                            <a href="https://codexlabbd.com" target="_blank" class="fw-bold text-white text-decoration-none">
                                CodexLab BD
                            </a>
                        </p>
                    </div>

                    <!-- Right: Payment Methods -->
                    <div class="col-md-3 text-center text-md-end">
                        <img src="{{ asset('frontEnd/images/payment2.png') }}" alt="Payment Methods"
                            class="img-fluid" style="max-height: 40px;">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    

    <!-- ===== Floating Contact Buttons (Left Side) ===== -->
   <style>
    .fixed-contact-toggle {
        margin-bottom : 50px;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 12px;
    }

    .fixed-contact-buttons {
        display: none;
        flex-direction: column;
        gap: 12px;
    }

    .fixed-contact-buttons a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background-color: #25D366;
        color: white;
        font-size: 22px;
        border-radius: 50%;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .fixed-contact-buttons a.messenger {
        background-color: #0084FF;
    }

    .fixed-contact-buttons a.phone {
        background-color: #28a745;
    }

    .fixed-contact-buttons a:hover,
    .message-toggle:hover {
        transform: scale(1.1);
        opacity: 0.9;
    }

    .message-toggle {
        width: 52px;
        height: 52px;
        background-color: #007bff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="fixed-contact-toggle">
    <!-- Toggle Button -->
  

    <!-- Hidden Contact Buttons -->
    <div class="fixed-contact-buttons" id="contactButtons">
        <!-- WhatsApp -->
        <a href="https://api.whatsapp.com/send?phone={{ $contact?->hotline }}" target="_blank" title="Chat on WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>

        <!-- Messenger -->
        <a href="https://m.me/{{ $contact?->facebook_page }}" target="_blank" class="messenger" title="Chat on Messenger">
            <i class="fa-brands fa-facebook-messenger"></i>
        </a>

        <!-- Phone -->
        <a href="tel:{{ $contact?->hotline }}" class="phone" title="Call Now">
            <i class="fa-solid fa-phone"></i>
        </a>
        
    </div>
      <div class="message-toggle" onclick="toggleContactButtons()" title="Contact Options">
        <i class="fa-solid fa-comment-dots"></i>
    </div>
</div>

<script>
    function toggleContactButtons() {
        const contactBox = document.getElementById('contactButtons');
        contactBox.style.display = contactBox.style.display === 'flex' ? 'none' : 'flex';
    }
</script>



    <!--=========-->
    <div class="footer_nav" >
        <ul>
            <li>
                <a class="toggle">
                    <span>
                        <i class="fa-solid fa-bars"></i>
                    </span>
                    <span>Category</span>
                </a>
            </li>

            <li>
                <a href="{{$contact?->facebook}}">
                    <span>
                        <i class="fa-brands fa-facebook"></i>
                    </span>
                    <span>Facebook</span>
                </a>
            </li>

            <li class="mobile_home" style="background:#117DB8 !important" >
                <a href="{{route('home')}}">
                    <span><i class="fa-solid fa-home"></i></span> <span>Home</span>
                </a>
            </li>

            <li>
                <a href="{{route('customer.checkout')}}">
                    <span>
                        <i class="fa-solid fa-cart-shopping"></i>
                    </span>
                    <span>Cart (<b class="mobilecart-qty">{{Cart::instance('shopping')->count()}}</b>)</span>
                </a>
            </li>
            @if(Auth::guard('customer')->user())
            <li>
                <a href="{{route('customer.account')}}">
                    <span>
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <span>Account</span>
                </a>
            </li>
            @else
            <li>
                <a href="{{route('customer.login')}}">
                    <span>
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <span>Login</span>
                </a>
            </li>
            @endif
        </ul>
    </div>


    <div class="scrolltop" style="">
        <div class="scroll">
            <i class="fa fa-angle-up"></i>
        </div>
    </div>

    <!-- /. fixed sidebar -->

    <div id="custom-modal"></div>
    <div id="page-overlay"></div>
 

    <script src="{{asset('frontEnd/js/jquery-3.6.3.min.js')}}"></script>
    <script src="{{asset('frontEnd/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('frontEnd/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('frontEnd/js/mobile-menu.js')}}"></script>
    <script src="{{asset('frontEnd/js/wsit-menu.js')}}"></script>
    <script src="{{asset('frontEnd/js/mobile-menu-init.js')}}"></script>
    <script src="{{asset('frontEnd/js/wow.min.js')}}"></script>
    <script>
        new WOW().init();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- feather icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{asset('backEnd/assets/js/toastr.min.js')}}"></script>
    {!! Toastr::message() !!}
    @stack('script')
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
                    url: "{{route('quickview')}}",
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
    <!-- cart js start -->
    <script>
      $(".addcartbutton").on("click", function() {
            var id = $(this).data("id");
            var qty = 1;
            if (id) {
                $.ajax({
                    cache: "false",
                    type: "GET",
                    url: "{{url('add-to-cart')}}/" + id + "/" + qty,
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                            toastr.success('Success', 'Product add to cart successfully');
                            return cart_count() + mobile_cart();
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
                    url: "{{route('cart.store')}}",
                    success: function(data) {
                        if (data) {
                            toastr.success('Success', 'Product add to cart succfully');
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(".cart_remove").on("click", function() {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{route('cart.remove')}}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart() + cart_summary();
                        }
                    },
                });
            }
        });

        $(".cart_increment").on("click", function() {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{route('cart.increment')}}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        $(".cart_decrement").on("click", function() {
            var id = $(this).data("id");
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{route('cart.decrement')}}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            return cart_count() + mobile_cart();
                        }
                    },
                });
            }
        });

        function cart_count() {
            $.ajax({
                type: "GET",
                url: "{{route('cart.count')}}",
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
                url: "{{route('mobile.cart.count')}}",
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
                url: "{{route('shipping.charge')}}",
                dataType: "html",
                success: function(response) {
                    $(".cart-summary").html(response);
                },
            });
        }
        function cart_summary() {
            $.ajax({
                type: "GET",
                url: "{{route('shipping.charge')}}",
                dataType: "html",
                success: function(response) {
                    $(".cart-summary").html(response);
                },
            });
        }
    </script>
    <!-- cart js end -->
    <script>
        $(document).ready(function() {
            $(document).on("submit", function(event) {
                if ($(event.target).is("#searchForm, #MainSearch")) {
                    event.preventDefault();

                    var keywordInput = $(event.target).find("input[name='keyword']");
                    var keyword = keywordInput.val().trim();

                    if (keyword === "") {
                        toastr.error('Please enter a search keyword.');
                        return false;
                    } else {
                        event.target.submit();
                    }
                }
            });
        });
        $(".search_click").on("keyup change", function() {
            var keyword = $(".search_keyword").val();
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{route('livesearch')}}",
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
                url: "{{route('livesearch')}}",
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
    </script>
 
    <script>
        $(".district").on("change", function() {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "{{route('districts')}}",
                success: function(res) {
                    if (res) {
                        $(".area").empty();
                        $(".area").append('<option value="">Select..</option>');
                        $.each(res, function(key, value) {
                            $(".area").append('<option value="' + key + '" >' + value + "</option>");
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

        $("#page-overlay").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
            $(".feature-products").removeClass("active");
        });

        $(".mobile-menu-close").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
        });

        $(".mobile-filter-toggle").on("click", function() {
            $("#page-overlay").show();
            $(".feature-products").addClass("active");
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

        //  Open the menu.
        document.querySelector('a[href="#menu"]').addEventListener("click", (evnt) => {
            evnt.preventDefault();
            drawer.open();
        });
    </script>
    <script>
        $(".filter_btn").click(function() {
            $(".filter_sidebar").addClass('active');
            $("body").css("overflow-y", "hidden");
        })
        $(".filter_close").click(function() {
            $(".filter_sidebar").removeClass('active');
            $("body").css("overflow-y", "auto");
        })
    </script>

<script>
    fetch('{{ route("facebook.pageview_capi") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            client_ip_address: "{{ request()->ip() }}",
            client_user_agent: navigator.userAgent
        })
    })
    .then(async res => {
        console.log(res);
        const data = await res.json();
        console.log('✅ CAPI PageView response:', data);
    })
    .catch(err => console.error('❌ CAPI PageView error:', err));
</script>

</body>
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

</html>
