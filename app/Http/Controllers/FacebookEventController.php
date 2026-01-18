<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookEventController extends Controller
{
   public function pageViewCAPI(Request $request)
    {
       
        $fbPixelId    = '1048739210676271';
        $fbAccessToken = 'EAAXzE0t81UMBPvW69NiCQ6I7MEk5nhdAtEE51WZCkbOhPOVZBbQhMkBWQwMUWT2jSIZANU2JY1JgDRh5mQPUyZCZATqzmJjbDVRyQEuGkTMJktSlXRCU7GFIJm1XTatK5eAj43JYtIryGIkKyCaKZCnRgqHwR5r8PNT46KvZBXZAf4605zD3fOjMXLR6hj0xAQZDZD';


        // Facebook Event Data
        $fbEventData = [
            'data' => [
                [
                    'event_name'       => 'PageView',
                    'event_time'       => time(),
                    'action_source'    => 'website',
                    'event_source_url' => $request->headers->get('referer', url()->current()),
                    'user_data'        => [
                        'client_ip_address' => $request->input('client_ip_address', $request->ip()),
                        'client_user_agent' => $request->input('client_user_agent', $request->userAgent()),
                    ],
                ]
            ]
        ];

        try {
            // Test Event Code as query parameter
            $fbResponse = Http::post(
                "https://graph.facebook.com/v18.0/{$fbPixelId}/events?access_token={$fbAccessToken}&test_event_code=TEST95897",
                $fbEventData
            );

            Log::info('📥 Facebook PageView Response:', $fbResponse->json());
        } catch (\Exception $e) {
            Log::error('❌ Facebook PageView CAPI Error: ' . $e->getMessage());
        }


        $ttPixelId = 'D3DVM0JC77U2RE92NOC0';
        $ttApiKey  = '2412ed71e78775c6c900f1a492132db5a2ec4145';

   

        $ttEventData = [
            'pixel_code'       => $ttPixelId,
            'event_source_id'  => $ttPixelId,
            'event_source'     => 'web',
            'data' => [
                [
                    'event'            => 'PageView',
                    'event_time'       => time(), // ✅ Required field added
                    'test_event_code'  => 'TEST77091',
                    'context'          => [
                        'ip'         => $request->input('client_ip_address', $request->ip()),
                        'user_agent' => $request->input('client_user_agent', $request->userAgent()),
                        'url'        => $request->headers->get('referer', url()->current()),
                    ],
                ]
            ]
        ];

      $ttResponse = null; // try এর আগে declare করে রাখুন
      $ttResponse = Http::withHeaders([
                'Access-Token' => $ttApiKey,
                'Content-Type'=> 'application/json'
            ])->post(
                "https://business-api.tiktok.com/open_api/v1.3/event/track/", 
                $ttEventData
            );

        return response()->json([
            'success' => true,
            'message' => 'PageView events sent to Facebook and TikTok (test events)',
            'facebook_response' => $fbResponse->json() ?? null,
            'tiktok_response' => $ttResponse->json() ?? null,
        ]);
    }

public function viewContent(Request $request)
{
    $fbPixelId     = '1048739210676271';
    $fbAccessToken = 'EAAXzE0t81UMBPvW69NiCQ6I7MEk5nhdAtEE51WZCkbOhPOVZBbQhMkBWQwMUWT2jSIZANU2JY1JgDRh5mQPUyZCZATqzmJjbDVRyQEuGkTMJktSlXRCU7GFIJm1XTatK5eAj43JYtIryGIkKyCaKZCnRgqHwR5r8PNT46KvZBXZAf4605zD3fOjMXLR6hj0xAQZDZD';

    $ttPixelId   = 'D3DVM0JC77U2RE92NOC0';
    $ttApiKey    = '1d908ee35b6d6d51ac6ad6026e7230c21e6416c9';

    $fbResponse = null;
    $ttResponse = null;

    try {
        // --------------------------
        // 📦 Facebook ViewContent
        // --------------------------
        $fbEventData = [
            'data' => [[
                'event_name'       => 'ViewContent',
                'event_time'       => time(),
                'event_id'         => $request->input('event_id'),
                'action_source'    => 'website',
                'event_source_url' => $request->headers->get('referer'),
                'user_data' => [
                    'client_ip_address' => $request->input('client_ip_address', $request->ip()),
                    'client_user_agent' => $request->input('client_user_agent', $request->userAgent()),
                ],
                'custom_data' => [
                    'content_ids'      => [$request->input('product_id')],
                    'content_name'     => $request->input('product_name'),
                    'content_type'     => 'product',
                    'content_category' => $request->input('category'),
                    'content_brand'    => $request->input('brand'),
                    'value'            => $request->input('value', 0),
                    'currency'         => $request->input('currency', 'BDT'),
                ]
            ]]
        ];

        $fbResponse = Http::post(
            "https://graph.facebook.com/v18.0/{$fbPixelId}/events?access_token={$fbAccessToken}",
            $fbEventData
        );

        Log::info('📥 Facebook View Content Response:', $fbResponse->json());


      
        $ttEventData = [
            'event_source'     => 'web', // ✅ required
            'event_source_id'  => $ttPixelId, // ✅ your pixel ID
            'test_event_code'  => 'TEST77091',
            'data' => [[
                'event'       => 'ViewContent',
                'event_time'  => time(),
                'context' => [
                    'ip'         => $request->input('client_ip_address', $request->ip()),
                    'user_agent' => $request->input('client_user_agent', $request->userAgent()),
                ],
                'properties' => [
                    'content_id'        => $request->input('product_id'),
                    'content_name'      => $request->input('product_name'),
                    'content_type'      => 'product',
                    'content_category'  => $request->input('category'),
                    'content_brand'     => $request->input('brand'),
                    'value'             => $request->input('value', 0),
                    'currency'          => $request->input('currency', 'BDT'),
                ]
            ]]
        ];

        $ttResponse = Http::withHeaders([
            'Access-Token' => $ttApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://business-api.tiktok.com/open_api/v1.3/event/track/', $ttEventData);

        Log::info('📥 TikTok View Content Response:', $ttResponse->json());


        // ✅ Return both responses
        return response()->json([
            'success'            => true,
            'message'            => 'ViewContent event sent successfully to Facebook and TikTok',
            'facebook_response'  => $fbResponse->json(),
            'tiktok_response'    => $ttResponse->json(),
        ]);

    } catch (\Exception $e) {
        Log::error('❌ ViewContent CAPI Error: ' . $e->getMessage());

        return response()->json([
            'success'           => false,
            'message'           => 'Error occurred while sending ViewContent events',
            'error'             => $e->getMessage(),
            'facebook_response' => $fbResponse?->json(),
            'tiktok_response'   => $ttResponse?->json(),
        ]);
    }
}





  
 
  
   public function addToCart(Request $request)
{
    // --- Facebook CAPI ---
    $fbPixelId     = '1048739210676271';
    $fbAccessToken = 'EAAXzE0t81UMBPJBE02nOFW1lnldaFl60OLbHB30Rpj5bYnaRn54rRZBjvF3FqSjADdlirk8s7UKAOs4dBBKV1JrCwVlhQ1b4H5YTg3RuQVWuuQTDofJqGrnVcuoA6cQkb1UTpTl8oNtZCiprMEv9MIB9js4lOO2yVYKVBATipZC32Kk9Ev5z5TLB02k4wZDZD';

    $fbEventData = [
        'data' => [[
            'event_name' => 'AddToCart',
            'event_time' => time(),
            'event_id'   => $request->event_id,
            'user_data'  => [
                'client_ip_address' => $request->client_ip_address ?? $request->ip(),
                'client_user_agent' => $request->client_user_agent ?? $request->userAgent(),
            ],
            'custom_data' => [
                'content_ids'  => [$request->product_id],
                'content_name' => $request->product_name,
                'content_category' => $request->category,
                'content_brand' => $request->brand,
                'value'        => $request->value,
                'currency'     => $request->currency,
                'contents' => [[
                    'id' => $request->product_id,
                    'quantity' => $request->quantity ?? 1,
                    'item_price' => ($request->quantity > 0 ? $request->value / $request->quantity : $request->value),
                ]]
            ]
        ]]
    ];

    $fbResponse = null;
    try {
        $fbResponse = Http::post("https://graph.facebook.com/v18.0/{$fbPixelId}/events?access_token={$fbAccessToken}", $fbEventData);
        Log::info('📥 Facebook CAPI AddToCart Response:', $fbResponse->json());
    } catch (\Exception $e) {
        Log::error('❌ Facebook CAPI AddToCart Error: ' . $e->getMessage());
    }

    // --- TikTok CAPI ---
    $ttPixelId = 'D3DVM0JC77U2RE92NOC0';
    $ttApiKey  = '1d908ee35b6d6d51ac6ad6026e7230c21e6416c9';

    $ttResponse = null;

    $ttEventData = [
        'event_source'    => 'web',
        'event_source_id' => $ttPixelId,
        'test_event_code' => 'TEST77091',
        'data' => [[
            'event'      => 'AddToCart',
            'event_time' => time(),
            'context'    => [
                'user' => [
                    'ip'         => $request->client_ip_address ?? $request->ip(),
                    'user_agent' => $request->client_user_agent ?? $request->userAgent(),
                ],
                'page' => [
                    'url' => $request->headers->get('referer', url()->current()),
                ],
            ],
            'properties' => [
                'contents' => [[
                    'content_id'      => $request->product_id,
                    'content_name'    => $request->product_name,
                    'content_category'=> $request->category,
                    'brand'           => $request->brand,
                    'quantity'        => (int) ($request->quantity ?? 1),
                    'price'           => (float) ($request->value ?? 0),
                ]],
                'currency' => $request->currency ?? 'BDT',
                'value'    => (float) ($request->value ?? 0),
            ]
        ]]
    ];

    try {
        $ttResponse = Http::withHeaders([
            'Access-Token' => $ttApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://business-api.tiktok.com/open_api/v1.3/event/track/', $ttEventData);

        Log::info('📥 TikTok CAPI AddToCart Response:', $ttResponse->json());
    } catch (\Exception $e) {
        Log::error('❌ TikTok CAPI AddToCart Error: ' . $e->getMessage());
    }

    // Null-safe response
    $tiktok_json = $ttResponse ? $ttResponse->json() : null;

    return response()->json([
        'success' => true,
        'message' => 'AddToCart event sent to Facebook and TikTok CAPI',
        'facebook_response' => $fbResponse ? $fbResponse->json() : null,
        'tiktok_response'  => $tiktok_json,
    ]);
}


public function beginCheckoutCAPI(Request $request)
{
    try {
        $ttPixelId = 'D3DVM0JC77U2RE92NOC0';
        $ttApiKey  = '1d908ee35b6d6d51ac6ad6026e7230c21e6416c9';

        $ttPayload = [
            'event_source'    => 'web',
            'event_source_id' => (string) $ttPixelId, // must be string
            'test_event_code' => 'TEST77091',
            'data' => [[
                'event'      => 'InitiateCheckout',
                'event_time' => time(),
                'context'    => [
                    'user' => [
                        'ip'         => $request->input('client_ip_address') ?? $request->ip(),
                        'user_agent' => $request->input('client_user_agent') ?? $request->userAgent(),
                    ],
                    'page' => [
                        'url' => $request->headers->get('referer', url()->current()),
                    ],
                ],
                'properties' => [
                    'value'    => (float) ($request->input('value') ?? 0),
                    'currency' => $request->input('currency', 'BDT'),
                    'contents' => collect($request->input('items', []))->map(function ($item) {
                        return [
                            'content_id' => $item['id'] ?? '',
                            'quantity'   => (int) ($item['quantity'] ?? 1),
                            'price'      => (float) ($item['price'] ?? 0),
                        ];
                    })->toArray(),
                ]
            ]]
        ];

        $ttResponse = null;
        try {
            $ttResponse = Http::withHeaders([
                'Access-Token' => $ttApiKey,
                'Content-Type' => 'application/json',
            ])
            ->withoutRedirecting() // prevent redirect errors
            ->post('https://business-api.tiktok.com/open_api/v1.3/event/track', $ttPayload);

            Log::info('📥 TikTok InitiateCheckout Response', [
                'payload' => $ttPayload,
                'response' => $ttResponse->json()
            ]);
        } catch (\Exception $e) {
            Log::error('❌ TikTok InitiateCheckout CAPI Error: ' . $e->getMessage());
        }

        $tiktok_json = $ttResponse ? $ttResponse->json() : null;

        return response()->json([
            'success' => true,
            'message' => 'InitiateCheckout event sent to TikTok CAPI',
            'tiktok_response' => $tiktok_json,
        ]);

    } catch (\Exception $e) {
        Log::error('❌ InitiateCheckout CAPI Outer Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function purchaseCAPI(Request $request)
{
    $fbPixelId     = '1048739210676271';
    $fbAccessToken = 'EAAXzE0t81UMBPJBE02nOFW1lnldaFl60OLbHB30Rpj5bYnaRn54rRZBjvF3FqSjADdlirk8s7UKAOs4dBBKV1JrCwVlhQ1b4H5YTg3RuQVWuuQTDofJqGrnVcuoA6cQkb1UTpTl8oNtZCiprMEv9MIB9js4lOO2yVYKVBATipZC32Kk9Ev5z5TLB02k4wZDZD';
    $ttPixelId     = 'D3DVM0JC77U2RE92NOC0';
    $ttApiKey      = '94c84832c4937034517fef00eb3eac1efbf2f5b6';

    $fbResponse = null;
    $ttResponse = null;

    try {
        // --- Facebook CAPI ---
        $fbContents = collect($request->items ?? [])->map(function($item){
            return [
                'id' => $item['id'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'item_price' => $item['price'] ?? 0
            ];
        })->toArray();

        $fbPayload = [
            'data' => [[
                'event_name'    => 'Purchase',
                'event_time'    => time(),
                'event_id'      => $request->event_id ?? null,
                'action_source' => 'website',
                'user_data'     => [
                    'client_ip_address' => $request->user_data['client_ip_address'] ?? $request->ip(),
                    'client_user_agent'=> $request->user_data['client_user_agent'] ?? $request->userAgent(),
                    'em' => isset($request->user_data['email']) ? hash('sha256', strtolower(trim($request->user_data['email']))) : null,
                    'ph' => isset($request->user_data['phone']) ? hash('sha256', preg_replace('/\D/', '', $request->user_data['phone'])) : null,
                ],
                'custom_data' => [
                    'currency' => $request->currency ?? 'BDT',
                    'value'    => (float) ($request->value ?? 0),
                    'contents' => $fbContents,
                ]
            ]]
        ];

        $fbResponse = Http::post("https://graph.facebook.com/v20.0/{$fbPixelId}/events?access_token={$fbAccessToken}", $fbPayload);
        Log::info('📥 Facebook CAPI Purchase Response:', $fbResponse->json());

    } catch (\Exception $e) {
        Log::error('❌ Facebook CAPI Purchase Error: ' . $e->getMessage());
    }

    try {
        // --- TikTok CAPI ---
        $ttContents = collect($request->items ?? [])->map(function($item){
            return [
                'content_id'      => $item['id'] ?? '',
                'content_name'    => $item['name'] ?? '',
                'content_category'=> $item['category'] ?? '',
                'brand'           => $item['brand'] ?? '',
                'quantity'        => $item['quantity'] ?? 1,
                'price'           => (float) ($item['price'] ?? 0),
                'item_size'       => $item['size'] ?? '',
                'item_color'      => $item['color'] ?? '',
                'item_model'      => $item['model'] ?? '',
                'item_weight'     => $item['weight'] ?? '',
            ];
        })->toArray();

        $ttPayload = [
            'event_source'    => 'web',
            'event_source_id' => (string) $ttPixelId,
            'test_event_code' => 'TEST77091',
            'data' => [[
                'event'      => 'Purchase',
                'event_time' => time(),
                'context'    => [
                    'user' => [
                        'ip'         => $request->user_data['client_ip_address'] ?? $request->ip(),
                        'user_agent' => $request->user_data['client_user_agent'] ?? $request->userAgent(),
                    ],
                    'page' => [
                        'url' => $request->headers->get('referer', url()->current()),
                    ],
                ],
                'properties' => [
                    'value'    => (float) ($request->value ?? 0),
                    'currency' => $request->currency ?? 'BDT',
                    'contents' => $ttContents,
                ]
            ]]
        ];

        $ttResponse = Http::withHeaders([
            'Access-Token' => $ttApiKey,
            'Content-Type'  => 'application/json',
        ])
        ->withoutRedirecting() // ✅ prevents "Will not follow more than 5 redirects"
        ->post('https://business-api.tiktok.com/open_api/v1.3/event/track', $ttPayload);

        Log::info('📥 TikTok CAPI Purchase Response:', [
            'payload'  => $ttPayload,
            'response' => $ttResponse->json()
        ]);

    } catch (\Exception $e) {
        Log::error('❌ TikTok CAPI Purchase Error: ' . $e->getMessage());
    }

    return response()->json([
        'success' => true,
        'facebook_response' => $fbResponse ? $fbResponse->json() : null,
        'tiktok_response'  => $ttResponse ? $ttResponse->json() : null,
        'message' => 'Purchase event sent to Facebook & TikTok CAPI'
    ]);
}


}