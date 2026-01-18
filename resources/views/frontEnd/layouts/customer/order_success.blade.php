@extends('frontEnd.layouts.master')
@section('title','Order Success')
@section('content')
<section class="customer-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="success-img">
     
                </div>
                <div class="success-title">
                    <h2> আপনার অর্ডারটি আমাদের কাছে সফলভাবে পৌঁছেছে, কিছুক্ষনের মধ্যে আমাদের একজন প্রতিনিধি আপনার নাম্বারে কল করবেন </h2>
                </div>

                <h5 class="my-3">Your Order Details</h5>
                <div class="success-table">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <p>Invoice ID</p>
                                    <p><strong>{{$order->invoice_id}}</strong></p>
                                </td>
                                <td>
                                    <p>Date</p>
                                    <p><strong>{{$order->created_at->format('d-m-y')}}</strong></p>
                                </td>
                                <td>
                                    <p>Phone</p>
                                    <p><strong>{{$order->shipping?$order->shipping->phone:''}}</strong></p>
                                </td>
                                <td>
                                    <p>Total</p>
                                    <p><strong>৳ {{$order->amount}}</strong></p>
                                </td>
                            </tr>
                            <tr>
                                @php 
                                    $payments = App\Models\Payment::where('order_id',$order->id)->first();
                                @endphp
                                <td colspan="4">
                                    <p>Payment Method</p>
                                    <p><strong>{{$payments->payment_method}}</strong></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- success table -->
                <h5 class="my-4">Pay with cash upon delivery</h5>
                <div class="success-table">
                    <h6 class="mb-3">Order Delivery</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderdetails as $key=>$value)
                            <tr>
                                <td>
                                    <p>{{$value->product_name}} x {{$value->qty}}</p>
                                    
                                </td>
                                <td><p><strong>৳ {{$value->sale_price}}</strong></p></td>
                            </tr>
                            @endforeach
                            <tr>
                                <th  class="text-end px-4">Net Total</th>
                                <td><strong id="net_total">৳{{$order->amount-$order->shipping_charge}}</strong></td>
                            </tr>
                            <tr>
                                <th  class="text-end px-4">Shipping Cost</th>
                                <td>
                                    <strong id="cart_shipping_cost">৳{{$order->shipping_charge}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th  class="text-end px-4">discount</th>
                                <td>
                                    <strong id="cart_shipping_cost">৳{{$order->discount}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th  class="text-end px-4">Grand Total</th>
                                <td>
                                    <strong id="grand_total">৳{{$order->amount}}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <h5 class="my-4">Billing Address</h5>
                                    <p>{{$order->shipping?$order->shipping->name:''}}</p>
                                    <p>{{$order->shipping?$order->shipping->phone:''}}</p>
                                    <p>{{$order->shipping?$order->shipping->address:''}}</p>
                                    <p>{{$order->shipping?$order->shipping->area:''}}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- success table -->

                <div class="success-home">
                    <a href="{{route('home')}}" class=" my-5 btn">Go To Home</a>
                </div>
            </div>
        </div>
    </div>
</section>

@php 

//dd($order->orderdetails);

@endphp
@endsection
@push('script')
<script src="{{asset('frontEnd/')}}/js/parsley.min.js"></script>
<script src="{{asset('frontEnd/')}}/js/form-validation.init.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Unique key for this order
        const orderKey = 'purchase_{{ $order->invoice_id }}';

        // Check if this order has already fired
        if (!sessionStorage.getItem(orderKey)) {
            // Mark this order as fired
            sessionStorage.setItem(orderKey, 'true');

            // Collect items
            const items = [
                @foreach ($order->orderdetails as $item)
                {
                    id: "{{ $item->product_id }}",
                    name: {!! json_encode($item->product_name) !!},
                    price: parseFloat({{ $item->sale_price }}),
                    brand: {!! json_encode($item->options->brand ?? 'N/A') !!},
                    category: {!! json_encode($item->options->category ?? 'N/A') !!},
                    size: {!! json_encode($item->product_size ?? '') !!},
                    color: {!! json_encode($item->product_color ?? '') !!},
                    model: {!! json_encode($item->product_model ?? '') !!},
                    weight: {!! json_encode($item->product_weight ?? '') !!},
                    quantity: {{ $item->qty ?? 1 }}
                }@if (!$loop->last),@endif
                @endforeach
            ];

            const totalValue = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const eventId = `purchase_{{ $order->invoice_id }}_${Date.now()}`;

            console.log('💰 Purchase triggered:', items, 'Total:', totalValue, 'EventID:', eventId);

            // --- Facebook Pixel (Browser-side) ---
            fbq('track', 'Purchase', {
                value: totalValue,
                currency: "BDT",
                contents: items.map(item => ({
                    id: item.id,
                    quantity: item.quantity,
                    item_price: item.price,
                    item_name: item.name
                }))
            }, { eventID: eventId });


             ttq.track('Purchase', {
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

            // --- Facebook Conversion API (Server-side) ---
            fetch('{{ route("facebook.purchase_capi") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    event_id: eventId,
                    transaction_id: "{{ $order->invoice_id }}",
                    value: totalValue,
                    currency: "BDT",
                    items: items,
                    user_data: {
                        name: {!! json_encode($order->shipping->name ?? '') !!},
                        phone: {!! json_encode($order->shipping->phone ?? '') !!},
                        email: {!! json_encode($order->shipping->email ?? '') !!},
                        address: {!! json_encode($order->shipping->address ?? '') !!},
                        area: {!! json_encode($order->shipping->area ?? '') !!},
                        country: "BD",
                        client_ip_address: "{{ request()->ip() }}",
                        client_user_agent: navigator.userAgent
                    }
                })
            })
            .then(async res => {
                const text = await res.text();
                try {
                    const data = JSON.parse(text);
                    console.log('✅ CAPI Purchase success:', data);
                } catch (err) {
                    console.error('❌ CAPI Purchase error. Raw response:', text);
                }
            })
            .catch(err => console.error('❌ CAPI Purchase fetch error:', err));
        } else {
            console.log('⚠️ Purchase already fired for order {{ $order->invoice_id }}');
        }
    });
</script>






@endpush