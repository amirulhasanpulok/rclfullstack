@extends('frontEnd.layouts.master') @section('title', 'Customer Checkout')



@push('css')
<style>
    .mobile-search {
        display: none;
    }
</style>
<style>
    .cart_table img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 8px;
    }

    .cart_table .product-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cart_table .quantity {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .cart_table .quantity input {
        width: 50px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .cart_table .quantity button {
        width: 30px;
        height: 30px;
        border: none;
        background-color: #f0f0f0;
        color: #000;
        font-weight: bold;
        border-radius: 4px;
    }

    .cart_table .quantity button:hover {
        background-color: #d3d3d3;
    }

    .cart_table tfoot th,
    .cart_table tfoot td {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .cart_table tr:hover {
        background-color: #f1f1f1;
    }

    .alinur {
        color: #198754;
        font-weight: 600;
    }

    .cart_remove {
        cursor: pointer;
        font-size: 18px;
    }

    .cart_remove:hover {
        color: #dc3545;
    }

    .cartlist .cart-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .cartlist .quantity {
        display: flex;
        align-items: center;
    }

    .cartlist .qty-input {
        width: 45px;
        border: none;
        background: #f8f9fa;
        font-weight: 600;
    }

    .cartlist .cart-item-row:hover {
        background-color: #f9f9ff;
        transition: 0.3s;
    }

    .cartlist .btn-sm {
        width: 28px;
        height: 28px;
        line-height: 1;
    }

    .card {
        border-radius: 1rem;
    }

    .alinur {
        color: #007bff;
        font-weight: bold;
    }
    @media (max-width: 991.98px) {  
    .chheckout-section {
        margin-top: 80px;
    }
}
  @media only screen and (min-width: 320px) and (max-width: 767px) {
        .footer_nav {
            display: none !important;
        }
       
    }
</style>

<link rel="stylesheet" href="{{ asset('frontEnd/css/select2.min.css') }}" />
@endpush @section('content')
<section  class="chheckout-section">
    @php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
    $discount = Session::get('discount')?Session::get('discount'):0;
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-sm-5 cus-order-2">
                <div class="checkout-shipping">
                    <style>
                        .compact-form .form-label {
                            font-size: 14px;
                            line-height: 1.2;
                            margin-bottom: 4px;
                        }

                        .compact-form input,
                        .compact-form select {
                            padding: 6px 10px;
                            font-size: 14px;
                            height: 36px;
                        }

                        .compact-form .card-header h6 {
                            font-size: 14px;
                            line-height: 1.4;
                            margin-bottom: 0;
                        }

                        .compact-form button {
                            font-size: 14px;
                            padding: 6px 18px;
                        }

                        .compact-form .form-control,
                        .compact-form .form-select {
                            border-radius: 0.25rem;
                        }

                        .compact-form .card-body {
                            padding: 16px;
                        }

                        .compact-form .card-header {
                            padding: 12px 16px;
                        }

                        .compact-form .row {
                            --bs-gutter-y: 0.5rem;
                        }
                    </style>
                    <style>
                        .area-option {
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            padding: 12px 16px;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            cursor: pointer;
                            transition: all 0.2s ease;
                            background-color: #fff;
                        }

                        .area-option:hover {
                            border-color: #0d6efd;
                            background-color: #f9fbff;
                        }

                        .area-option input[type="radio"] {
                            display: none;
                        }

                        .area-option.selected {
                            border-color: #0d6efd;
                            background-color: #e8f0ff;
                        }

                        .area-name {
                            font-weight: 600;
                            font-size: 15px;
                            color: #333;
                        }

                        .area-price {
                            font-weight: 600;
                            color: #555;
                        }
                    </style>
                    <form action="{{ route('customer.ordersave') }}" method="POST" data-parsley-validate=""
                        class="compact-form">
                        @csrf
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light border-bottom">
                                <h6 class="text-dark fw-bold">
                                    অর্ডার করতে নিচের ফর্মটি পূরণ করুন
                                </h6>
                            </div>

                            <div class="card-body">
                                <div class="row g-2">

                                    {{-- Name --}}
                                    <div class="col-md-12">
                                        <label for="name" class="form-label fw-semibold">আপনার নাম লিখুন *</label>
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Phone --}}
                                    <div class="col-md-12">
                                        <label for="phone" class="form-label fw-semibold">আপনার নাম্বার লিখুন *</label>
                                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                            minlength="11" maxlength="11" pattern="0[0-9]+"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            title="০ দিয়ে শুরু করে ১১ ডিজিটের নাম্বার দিন" required>
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Address --}}
                                    <div class="col-md-12">
                                        <label for="address" class="form-label fw-semibold">ঠিকানা লিখুন *</label>
                                        <input type="text" id="address" name="address" value="{{ old('address') }}"
                                            class="form-control @error('address') is-invalid @enderror" required>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Area --}}
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label fw-semibold mb-2">এলাকা নির্বাচন করুন *</label>

                                        <div class="d-flex flex-column gap-2">
                                            @foreach ($shippingcharge as $key => $value)
                                            <label class="area-option" for="area-{{ $value->id }}">
                                                <div class="area-name">
                                                    <input type="radio" name="area" id="area-{{ $value->id }}"
                                                        value="{{ $value->id }}" required {{ old('area')==$value->id ?
                                                    'checked' : '' }}
                                                    >
                                                    {{ $value->name }}
                                                </div>
                                                <div class="area-price">৳{{ $value->amount }}</div>
                                            </label>
                                            @endforeach
                                        </div>

                                        @error('area')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    






                                  
                                    <div class="col-md-12 text-center mt-2">
                                        <button type="submit"
                                            class="btn btn-success orderNowBtn rounded-pill shadow-sm w-100">
                                            <i class="fa-solid fa-check-circle me-1 "></i> অর্ডার করুন
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- col end -->
            <div class="col-sm-7 cust-order-1">
                <div class="cart_details table-responsive-sm">
                    <div class="card">
                        <div class="card-header">
                            <h5>অর্ডারের তথ্য</h5>
                        </div>
                        <div class="card-body cartlist">
                            <style>
                                .cart_table img {
                                    width: 60px;
                                    height: 60px;
                                    object-fit: cover;
                                    border-radius: 6px;
                                    margin-right: 8px;
                                }

                                .cart_table .product-info {
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                }

                                .cart_table .quantity {
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 6px;
                                }

                                .cart_table .quantity input {
                                    width: 50px;
                                    text-align: center;
                                    border: 1px solid #ddd;
                                    border-radius: 4px;
                                }

                                .cart_table .quantity button {
                                    width: 30px;
                                    height: 30px;
                                    border: none;
                                    background-color: #f0f0f0;
                                    color: #000;
                                    font-weight: bold;
                                    border-radius: 4px;
                                }

                                .cart_table .quantity button:hover {
                                    background-color: #d3d3d3;
                                }

                                .cart_table tfoot th,
                                .cart_table tfoot td {
                                    background-color: #f8f9fa;
                                    font-weight: bold;
                                }

                                .cart_table tr:hover {
                                    background-color: #f1f1f1;
                                }

                                .alinur {
                                    color: #198754;
                                    font-weight: 600;
                                }

                                .cart_remove {
                                    cursor: pointer;
                                    font-size: 18px;
                                }

                                .cart_remove:hover {
                                    color: #dc3545;
                                }
                            </style>

                            <table class="cart_table table table-bordered table-striped text-center mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 10%;">ডিলিট</th>
                                        <th style="width: 75%;">প্রোডাক্ট</th>
                                       
                                        <th style="width: 15%;">মূল্য</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach (Cart::instance('shopping')->content() as $value)
                                    <tr>
                                        <td>
                                            <a class="cart_remove text-danger" data-id="{{ $value->rowId }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                        <td class="text-start">
                                            <a href="{{ route('product', $value->options->slug) }}"
                                                class="text-dark text-decoration-none">
                                                <div class="product-info">
                                                    <img src="{{ asset($value->options->image) }}"
                                                        alt="{{ $value->name }}">
                                                    <div>
                                                        <div class="fw-semibold">{{ Str::limit($value->name, 35) }}
                                                        </div>
                                                        <div class="small text-muted">
                                                            @if ($value->options->product_size)
                                                            <span>Size: {{ $value->options->product_size }} | </span>
                                                            @endif
                                                            @if ($value->options->product_color)
                                                            <span>Color: {{ $value->options->product_color }} | </span>
                                                            @endif
                                                            @if ($value->options->product_model)
                                                            <span>Model: {{ $value->options->product_model }} | </span>
                                                            @endif
                                                            @if ($value->options->product_weight)
                                                            <span>Weight: {{ $value->options->product_weight }}</span>
                                                            @endif

                                                            @if ($value->options->writer_id)
                                                            <span>Writer: {{ $value->options->writer_id }}</span>
                                                            @endif

                                                            @if ($value->options->publisher_id)
                                                            <span>publisher: {{ $value->options->publisher_id }}</span>
                                                            @endif
                                                            @if ($value->options->subject_id)
                                                            <span>suject: {{ $value->options->subject_id }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                             <div class="quantity">
                                                <button class="cart_decrement" data-id="{{ $value->rowId }}">−</button>
                                                <input type="text" value="{{ $value->qty }}" readonly />
                                                <button class="cart_increment" data-id="{{ $value->rowId }}">+</button>
                                            </div>
                                        </td>

                                   

                                        <td>
                                            <span class="alinur">৳</span>
                                            <strong>{{ $value->price }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-end px-4">মোট</th>
                                        <td class="px-4">
                                            <span class="alinur">৳</span>
                                            <strong>{{ $subtotal }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-end px-4">ডেলিভারি চার্জ</th>
                                        <td class="px-4">
                                            <span class="alinur">৳</span>
                                            <strong>{{ $shipping }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-end px-4">ডিসকাউন্ট</th>
                                        <td class="px-4">
                                            <span class="alinur">৳</span>
                                            <strong>{{ $discount + $coupon }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-end px-4">সর্বমোট</th>
                                        <td class="px-4">
                                            <span class="alinur">৳</span>
                                            <strong>{{ $subtotal + $shipping - ($discount + $coupon) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                               <form
                                        action="@if(Session::get('coupon_used')) {{ route('customer.coupon_remove') }} @else {{ route('customer.coupon') }} @endif"
                                        method="POST" class="checkout-coupon-form mt-3">
                                        @csrf

                                        <div class="card border-0 shadow-sm rounded-3">
                                            <div class="card-body p-3">


                                                <div class="input-group">
                                                    <input type="text" name="coupon_code"
                                                        class="form-control border-primary shadow-none py-2 px-3 fw-semibold @if(Session::get('coupon_used')) text-success @endif"
                                                        placeholder="@if(Session::get('coupon_used')) {{ Session::get('coupon_used') }} @else Apply Coupon Code @endif"
                                                        @if(Session::get('coupon_used')) readonly @endif>

                                                    <button type="submit"
                                                        class="btn @if(Session::get('coupon_used')) btn-danger @else btn-primary @endif px-4 fw-semibold">
                                                        @if(Session::get('coupon_used')) Remove @else Apply @endif
                                                    </button>
                                                </div>

                                                @if(Session::get('coupon_used'))
                                                <div class="mt-2 text-success small">
                                                    ✅ Coupon "<strong>{{ Session::get('coupon_used') }}</strong>"
                                                    applied successfully!
                                                </div>
                                                @endif

                                                @error('coupon_code')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->
        </div>
    </div>
</section>
@php
//dd(Cart::instance('shopping')->content());

@endphp
@endsection @push('script')
<script src="{{ asset('frontEnd') }}/js/parsley.min.js"></script>
<script src="{{ asset('frontEnd') }}/js/form-validation.init.js"></script>
<script src="{{ asset('frontEnd') }}/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>
<script>
    $(document).on("change", "input[name='area']", function() {
        var id = $(this).val();
        $.ajax({
            type: "GET",
            url: "{{ route('shipping.charge') }}",
            data: { id: id },
            success: function(response) {
                $(".cartlist").html(response);
            },
            error: function(xhr) {
                console.error("Error loading shipping charge:", xhr);
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const items = [
        @foreach(Cart::instance('shopping')->content() as $cartInfo)
        {
        id: "{{ $cartInfo->id }}",
        name: {!! json_encode($cartInfo->name) !!},
        price: {{ $cartInfo->price }},
        size: {!! json_encode($cartInfo->options->product_size ?? '') !!},
        color: {!! json_encode($cartInfo->options->product_color ?? '') !!},
        model: {!! json_encode($cartInfo->options->product_model ?? '') !!},
        weight: {!! json_encode($cartInfo->options->product_weight ?? '') !!},
        quantity: {{ $cartInfo->qty ?? 0 }}
        }@if(!$loop->last),@endif
        @endforeach
    ];

    const totalValue = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    // Unique event ID for deduplication
    const eventId = `begincheckout_{{ session()->getId() }}_${Date.now()}`;

    console.log('🛒 BeginCheckout triggered:', items, 'Total:', totalValue, 'EventID:', eventId);

    // --- Facebook Pixel (browser-side) ---
    fbq('track', 'InitiateCheckout', {
        currency: "BDT",
        value: totalValue,
        contents: items.map(item => ({
        id: item.id,
        name: item.name,
        quantity: item.quantity,
        price: item.price
        }))
    }, {
        eventID: eventId
    });


    ttq.track('InitiateCheckout', {
        value: totalValue,
        currency: 'BDT',
        contents: items.map(item => ({
            content_id: item.id,
            content_name: item.name,
            content_type: 'product',
            quantity: item.quantity,
            price: item.price
        }))
    });

    // --- Facebook Conversion API (server-side) ---
    fetch('{{ route("facebook.begin_checkout_capi") }}', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
        event_id: eventId,
        currency: "BDT",
        value: totalValue,
        items: items,
        client_user_agent: navigator.userAgent,
        client_ip_address: "{{ request()->ip() }}"
        })
    })
    .then(async res => {
        const text = await res.text();
        try {
        const data = JSON.parse(text);
        console.log('✅ CAPI BeginCheckout success response:', data);
        } catch (err) {
        console.error('❌ CAPI BeginCheckout error. Raw response:', text);
        }
    })
    .catch(err => console.error('❌ CAPI BeginCheckout fetch error:', err));
    });
</script>








@endpush