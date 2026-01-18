<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\District;
use App\Models\CreatePage;
use App\Models\Campaign;
use App\Models\Banner;
use App\Models\ShippingCharge;
use App\Models\Productcolor;
use App\Models\Productsize;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\OrderDetails;
use App\Models\ProductVariable;
use App\Models\Order;
use App\Models\Review;
use Session;
use Cart;
use Auth;
use Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http; // for Laravel Http client
use Illuminate\Support\Str;
use Log;
class FrontendController extends Controller
{
    public function index()
    {
        $data = Cache::remember('home_page_data', 3600, function () {
            $frontcategory = Category::where(['status' => 1])
                ->select('id', 'name', 'image', 'slug', 'status','icon')
                ->get();

            $sliders = Banner::where(['status' => 1, 'category_id' => 1])
                ->select('id', 'image', 'link')
                ->get();
                

            $hotdeal_top = Product::where(['status' => 1, 'topsale' => 1])
                ->orderBy('id', 'DESC')
                ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
                ->withCount('variable')
                ->limit(12)
                ->get();

            $newArrival = Product::where('status', 1)
                ->orderBy('id', 'DESC')
                ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
                ->withCount('variable')
                ->limit(7)
                ->get();

           

          $homecategory = Category::where([ 'front_view' => 1, 'status' => 1])
                ->with(['products' => function ($query) {
                    $query->where('status', 1)
                        ->orderBy('id', 'DESC')
                        ->with('image', 'variable')
                        ->withCount('variable')
                        ->take(30);
                }])
                ->orderBy('id', 'ASC')
                ->get();


              //  dd($homecategory);
                $featured = Category::where([ 'status' => 1, 'featured' => 1])
                ->with(['products' => function ($query) {
                    $query->where('status', 1)
                        ->orderBy('id', 'DESC')
                        ->with('image', 'variable')
                        ->withCount('variable')
                        ->limit(8);
                }])
                ->orderBy('id', 'ASC')
                ->get();

            return [
                'newArrival'=>$newArrival,
                'sliders' => $sliders,
                'frontcategory' => $frontcategory,
                'hotdeal_top' => $hotdeal_top,
                'homecategory' => $homecategory,
                'featured' => $featured
            ];
        });

        return view('frontEnd.layouts.pages.index', $data);
    }


    public function hotdeals(Request $request)
    {

        $products = Product::where(['status' => 1, 'topsale' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')->withCount('variable');
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(24);

        return view('frontEnd.layouts.pages.hotdeals', compact('products', 'min_price', 'max_price'));
    }

    public function category($slug, Request $request)
    {
        $category = Category::where(['slug' => $slug, 'status' => 1])->first();

        $products = Product::where(['status' => 1, 'category_id' => $category->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')->withCount('variable');
        $subcategories = Subcategory::where('category_id', $category->id)->get();

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(12);
        return view('frontEnd.layouts.pages.category', compact('category', 'products', 'subcategories', 'min_price', 'max_price'));
    }

    public function subcategory($slug, Request $request)
    {
        $subcategory = Subcategory::where(['slug' => $slug, 'status' => 1])->first();

        $products = Product::where(['status' => 1, 'subcategory_id' => $subcategory->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'subcategory_id')->withCount('variable');
        $childcategories = Childcategory::where('subcategory_id', $subcategory->id)->get();
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(12);

        return view('frontEnd.layouts.pages.subcategory', compact('subcategory', 'products', 'childcategories', 'max_price', 'min_price'));
    }

    public function products($slug, Request $request)
    {
        $childcategory = Childcategory::where(['slug' => $slug, 'status' => 1])->first();
        $childcategories = Childcategory::where('subcategory_id', $childcategory->subcategory_id)->get();

        $products = Product::where(['status' => 1, 'subcategory_id' => $childcategory->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'subcategory_id')->withCount('variable');

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(12);

        return view('frontEnd.layouts.pages.childcategory', compact('childcategory', 'products', 'min_price', 'max_price', 'childcategories'));
    }


    public function details($slug)
    {
        Log::info('product view');
        // Get product details
        $data['details'] = Product::where(['slug' => $slug, 'status' => 1])
            ->with('image', 'images', 'category', 'subcategory', 'childcategory')
            ->withCount('variable')
            ->firstOrFail();

        // Related products
        $data['products'] = Product::where(['category_id' => $data['details']->category_id, 'status' => '1'])
            ->with('image')
            ->select('id', 'name', 'slug', 'status', 'category_id', 'new_price', 'old_price', 'type')
            ->withCount('variable')
            ->limit(6)
            ->get();

        // Shipping charges
        $data['shippingcharge'] = ShippingCharge::where('status', 1)->get();

        // Reviews
        $data['reviews'] = Review::where('product_id', $data['details']->id)->get();

        // Product variations
        $data['productcolors'] = ProductVariable::where('product_id', $data['details']->id)
            ->where('stock', '>', 0)
            ->whereNotNull('color')
            ->select('color')
            ->distinct()
            ->get();

        $data['productsizes'] = ProductVariable::where('product_id', $data['details']->id)
            ->where('stock', '>', 0)
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->get();

        $data['productmodel'] = ProductVariable::where('product_id', $data['details']->id)
            ->where('stock', '>', 0)
            ->whereNotNull('model')
            ->select('model')
            ->distinct()
            ->get();

        $data['productweight'] = ProductVariable::where('product_id', $data['details']->id)
            ->where('stock', '>', 0)
            ->whereNotNull('weight')
            ->select('weight')
            ->distinct()
            ->get();

        // Contact and page info
        $data['contact'] = Contact::where('status', 1)->first();
        $data['page'] = CreatePage::find(5);
        return view('frontEnd.layouts.pages.details', $data);
    }
    public function stock_check(Request $request)
    {
        $query = ProductVariable::where('product_id', $request->id);

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('weight')) {
            $query->where('weight', $request->weight);
        }

        $product = $query->first();

        $response = [
            'status' => (bool) $product,
            'product' => $product
        ];

        return response()->json($response);
    }

    public function quickview(Request $request)
    {
        $data['data'] = Product::where(['id' => $request->id, 'status' => 1])->with('images')->withCount('reviews')->first();
        $data = view('frontEnd.layouts.ajax.quickview', $data)->render();
        if ($data != '') {
            echo $data;
        }
    }
    public function livesearch(Request $request)
    {

        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
            ->where('status', 1)
            ->withCount('variable')
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->get();

        if (empty($request->category) && empty($request->keyword)) {
            $products = [];
        }
        return view('frontEnd.layouts.ajax.search', compact('products'));
    }
    public function search(Request $request)
    {
        $products = Product::where('status', 1)
            ->with('image')
            ->select('id', 'name', 'slug', 'status', 'category_id', 'new_price', 'old_price', 'type')
            ->withCount('variable');

        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }

        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }

        $products = $products->paginate(12);
        $keyword = $request->keyword;

        return view('frontEnd.layouts.pages.search', compact('products', 'keyword'));
    }

    public function shipping_charge(Request $request)
    {

        $shipping = ShippingCharge::where(['id' => $request->id])->first();
        Session::put('shipping', $shipping->amount);
        return view('frontEnd.layouts.ajax.cart');
    }


    public function contact(Request $request)
    {
        return view('frontEnd.layouts.pages.contact');
    }

    public function page($slug)
    {
        $page = CreatePage::where('slug', $slug)->firstOrFail();
        return view('frontEnd.layouts.pages.page', compact('page'));
    }
    public function districts(Request $request)
    {
        $areas = District::where(['district' => $request->id])->pluck('area_name', 'id');
        return response()->json($areas);
    }
    public function campaign($slug, Request $request)
    {

        $campaign = Campaign::where('slug', $slug)->with('images')->first();

        $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock')->where(['id' => $campaign->product_id])->first();
        // return $product;
        $productcolors = ProductVariable::where('product_id', $campaign->product_id)->where('stock', '>', 0)
            ->whereNotNull('color')
            ->select('color')
            ->distinct()
            ->get();

        $productsizes = ProductVariable::where('product_id', $campaign->product_id)->where('stock', '>', 0)
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->get();

        Cart::instance('shopping')->destroy();


        $var_product = ProductVariable::where(['product_id' => $campaign->product_id])->first();
        if ($product->type == 0) {
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->new_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $stock = $product->stock;
        }

        $qty = 1;
        $cartitem = Cart::instance('shopping')->content()->where('id', $product->id)->first();

        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->product_size,
                'product_color' => $request->product_color,
                'type' => $product->type
            ],
        ]);
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $select_charge = ShippingCharge::where('status', 1)->first();
        Session::put('shipping', $select_charge->amount);
        return view('frontEnd.layouts.pages.campaign.campaign', compact('campaign', 'productsizes', 'productcolors', 'shippingcharge', 'old_price', 'new_price'));
    }
    public function campaign_stock(Request $request)
    {
        $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock')->where(['id' => $request->id])->first();

        $variable = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();
        $qty = 1;
        $status = $variable ? true : false;

        if ($status == true) {
            // return $variable;
            // return "wait";
            Cart::instance('shopping')->destroy();
            Cart::instance('shopping')->add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $qty,
                'price' => $variable->new_price,
                'options' => [
                    'slug' => $product->slug,
                    'image' => $product->image->image,
                    'old_price' => $variable->new_price,
                    'purchase_price' => $variable->purchase_price,
                    'product_size' => $request->size,
                    'product_color' => $request->color,
                    'type' => $product->type
                ],
            ]);
        }
        $data = Cart::instance('shopping')->content();
        return response()->json($status);

        return view('frontEnd.layouts.ajax.cart', compact('data'));
        $response = [
            'status' => $status,
            'data' => $data
        ];
        return response()->json($response);
    }

    public function payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        if ($data[0]->sp_code != 1000) {
            Toastr::error('Your payment failed, try again', 'Oops!');
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }

        if ($data[0]->value1 == 'customer_payment') {

            $customer = Customer::find(Auth::guard('customer')->user()->id);

            // order data save
            $order = new Order();
            $order->invoice_id = $data[0]->id;
            $order->amount = $data[0]->amount;
            $order->customer_id = Auth::guard('customer')->user()->id;
            $order->order_status = $data[0]->bank_status;
            $order->save();

            // payment data save
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = Auth::guard('customer')->user()->id;
            $payment->payment_method = 'shurjopay';
            $payment->amount = $order->amount;
            $payment->trx_id = $data[0]->bank_trx_id;
            $payment->sender_number = $data[0]->phone_no;
            $payment->payment_status = 'paid';
            $payment->save();
            // order details data save
            foreach (Cart::instance('shopping')->content() as $cart) {
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $cart->id;
                $order_details->product_name = $cart->name;
                $order_details->purchase_price = $cart->options->purchase_price;
                $order_details->sale_price = $cart->price;
                $order_details->qty = $cart->qty;
                $order_details->save();
            }

            Cart::instance('shopping')->destroy();
            Toastr::error('Thanks, Your payment send successfully', 'Success!');
            return redirect()->route('home');
        }

        Toastr::error('Something wrong, please try agian', 'Error!');
        return redirect()->route('home');
    }
    public function payment_cancel(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        Toastr::error('Your payment cancelled', 'Cancelled!');
        if ($data[0]->sp_code != 1000) {
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function offers()
    {
        return view('frontEnd.layouts.pages.offers');
    }

    public function contact_submit(Request $request)
    {
        dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'phone' => 'required',
        ]);

        $data = array(
            'cus_name' => $request->name,
            'cus_email' => $request->email,
            'cus_phone' => $request->phone,
            'cus_subject' => $request->subject,
            'cus_message' => $request->message,
        );

        // $send = Mail::send('emails.email', $data, function ($textmsg) use ($data) {
        //     $textmsg->from($data['cus_email']);
        //     $textmsg->to('admin@projapotishop.com');
        //     $textmsg->subject($data['cus_subject']);
        // });

        Toastr::success('Thanks, Message send successfully', 'Success!');
        return redirect()->back();
    }
}