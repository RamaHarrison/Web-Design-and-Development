<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                ->whereIn('status_id', [1, 2, 3, 4, 6])
                ->orderBy('created_at', 'desc')
                ->get();
        return view('order', ['orders' => $orders]);
    }

    public function history()
    {
        $orders = Order::with(['orderItems.menuItem'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('history', [
            'orders' => $orders,
        ]);
    }
    
    public function store(Request $request) {
        // dd($request->all());

        if($request->menuItem_ids == null) {
            return redirect()->back()->with('error', 'Cart is empty');
        }

        if ($request->option == 1 && empty($request->address)) {
            return redirect()->back()->with('error', 'Address is required for delivery');
        }

        $order = new Order();
        $order->user_id = Auth::id();
        $order->delivery_id = $request->option;
        $order->address = $request->address;
        $order->total = $request->total;
        $order->save();

        foreach ($request->menuItem_ids as $key => $value) {
            $order_item = new OrderItem();
            $order_item->order_id = $order->id;
            $order_item->menu_item_id = $value;
            $order_item->quantity = $request->quantities[$key];
            $order_item->size_id = $request->cart_item_sizes[$key];
            try {
                $order_item->save();
            } 
            catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
        ]);

        try {
            $deleteCart = app(CartController::class)->destroyCart($request->cart_id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete cart');
        }

        return redirect()->back()->with('success', 'Order created successfully');
    }

    public function createPayment(Request $request) {

        dd($request->all());

        $request->validate([
            'order_id' => 'required:exists:orders,id',
            'payment_method' => 'required',
        ]);

        Payment::create([
            'order_id' => $request->order_id,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->back()->with('success', 'Payment created successfully');
    }
}
