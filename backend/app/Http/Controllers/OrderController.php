<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Str;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('order_details.product:id,name,img_path')->where('user_id', $request->user_id)->get();

        return response()->json($orders, 200);
    }


    public function store(StoreOrderRequest $request)
    {
        try {
            foreach ($request->products as $product) {
                $good = Product::find($product['id']);
                if ($product['quantity'] > $good->stock) {
                    throw new Exception('在庫不足');
                }
                if ($product['price'] != $good->price) {
                    throw new Exception('価格変更');
                }
            }

            $newOrder = [
                'user_id' => $request->user_id,
                'uuid' => (string) Str::uuid(),
            ];
            $order = Order::create($newOrder);

            foreach ($request->products as $product) {
                $orderDetail = [
                    'order_uuid' => $order->uuid,
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price_fixed' => $product['price'],
                ];
                OrderDetail::create($orderDetail);
                $good = Product::find($product['id']);
                $good->update([
                    'stock' => ($good->stock - $product['quantity'])
                ]);
            }

            return response()->json([
                'order_uuid' => $order->uuid,
            ], 201);
        } catch (Exception) {
            return response()->json(200);
        }
    }
}
