<?php

namespace App\Http\Controllers\Invoicing;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();

        return Inertia::render('invoicing/products/Index', [
            'products' => $products,
        ]);
    }

    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.key'));

        $products = Product::all();
        $lineItems = [];
        $totalPrice = 0;

        foreach($products as $product) {
            $totalPrice += $product->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->name,
                        'images' => [$product->image]
                    ],
                    'unit_amount' => $product->price*100
                ],
                'quantity' => 1,
            ];
        }

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $totalPrice;
        $order->session_id = $checkout_session->id;
        $order->save();

        return Inertia::location($checkout_session->url);
    }

    public function success(Request $request) {
        \Stripe\Stripe::setApiKey(config('services.stripe.key'));

        $sessionId = $request->get('session_id');
        $customer = null;

        try{
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if(!$session) {
                throw new NotFoundHttpException;
            }
            $customer = $session->customer_details;
            $order = Order::where('session_id', $session->id)->first();
            if(!$order) {
                throw new NotFoundHttpException;
            }
            if($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }

            return Inertia::render('admin/products/Success', [
                'customer' => $customer,
                'session' => $session,
                'order' => $order,
            ]);
        } catch(\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
    public function cancel() {
        return Inertia::render('invoicing/products/Index', [
            'products' => Product::all(),
        ]);
    }

    public function webhook() {

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

// Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $order = Order::where('session_id', $session->id)->first();
                if ($order && $order->status === 'unpaid') {
                    $order->status = 'paid';
                    $order->save();
                    // Send email to customer
                }

            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}
