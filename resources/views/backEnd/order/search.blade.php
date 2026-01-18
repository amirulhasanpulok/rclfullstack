@if($products)
<div class="search_product">
    <ul>
        @foreach($products as $value)
            @if($value->type == 1) 
                {{-- Simple Product --}}
                <li>
                    <a data-id="{{$value->id}}" class="cart_add">
                        <p class="name">{{$value->name}}</p>
                        <p class="price">
                            ৳{{$value->new_price}}
                            @if($value->old_price)<del>৳{{$value->old_price}}</del>@endif
                        </p>
                    </a>
                </li>
            @else
                {{-- Variation Product --}}
                @foreach($value->variables as $variable)
                <li>
                    <a data-id="{{$value->id}}" 
                       data-size="{{$variable->size}}" 
                       data-color="{{$variable->color}}" 
                       data-weight="{{$variable->weight}}" 
                       data-model="{{$variable->model}}" 
                       class="cart_add">

                        <p class="name">
                            {{$value->name}}

                            {{-- Show selected variations dynamically --}}
                            @if($variable->size) - Size: {{$variable->size}} @endif
                            @if($variable->color) - Color: {{$variable->color}} @endif
                            @if($variable->weight) - Weight: {{$variable->weight}} @endif
                            @if($variable->model) - Model: {{$variable->model}} @endif

                            (Stock: {{$variable->stock}})
                        </p>

                        <p class="price">
                            ৳{{$variable->new_price}}
                            @if($variable->old_price)<del>৳{{$variable->old_price}}</del>@endif
                        </p>
                    </a>
                </li>
                @endforeach
            @endif
        @endforeach
    </ul>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function cart_content() {
    $.ajax({
        type: "GET",
        url: "{{route('admin.order.cart_content')}}",
        dataType: "html",
        success: function (cartinfo) {
            $("#cartTable").html(cartinfo);
        }
    });
}
function cart_details() {
    $.ajax({
        type: "GET",
        url: "{{route('admin.order.cart_details')}}",
        dataType: "html",
        success: function (cartinfo) {
            $("#cart_details").html(cartinfo);
        }
    });
}
function search_clear() {
    var keyword = '';
    $.ajax({
        type: "GET",
        data: { keyword: keyword },
        url: "{{route('admin.livesearch')}}",
        success: function (products) {
            if (products) {
                $(".search_result").html(products);
                $('.search_click').val('');
            } else {
                $(".search_result").empty();
                $('.search_click').val('');
            }
        }
    });
}

$(document).on("click", ".cart_add", function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var color = $(this).data('color');
    var size = $(this).data('size');
    var weight = $(this).data('weight');
    var model = $(this).data('model');

    if (id) {
        $.ajax({
            type: "GET",
            url: "{{route('admin.order.cart_add')}}",
            data: {
                id: id,
                color: color,
                size: size,
                weight: weight,
                model: model
            },
            dataType: "json",
            success: function (cartinfo) {
                cart_content();
                cart_details();
                search_clear();
            }
        });
    }
});
</script>