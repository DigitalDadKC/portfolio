<?php

namespace App\Http\Controllers\Admin;

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

        return Inertia::render('admin/products/Index', [
            'products' => $products,
        ]);
    }

    public function checkout(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_KEY'));

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
            'line_items' => [[$lineItems]],
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
            $order = Order::where('session_id', $session->id)->first();
            // $customer = \Stripe\Customer::retrieve($session->customer);

            return Inertia::render('admin/products/Success', [
                'customer' => $customer
            ]);
        } catch(\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function cancel() {

    }
}
