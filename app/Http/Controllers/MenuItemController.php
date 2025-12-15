<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Categories;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class menuItemController extends Controller
{
    public function index()
    {
        //get all menu items
        $categories = Categories::all();
        $menuItems = MenuItem::with('category')->get();
        
        //if user is not logged in, show menu without cart
        if (!Auth::check()) {
            return view('menu', [
                'categories' => $categories,
                'menuItems' => $menuItems,
            ]);
        }
        
        //if user is logged in, show menu with cart 
        $cartData = app(CartController::class)->show();

        return view('menu', [
            'categories' => $categories,
            'menuItems' => $menuItems,
            'cart' => $cartData['cart'],
            'cartItems' => $cartData['cartItems'],
            'subtotal' => $cartData['subtotal'],
            'tax' => $cartData['tax'],
            'delivery' => $cartData['delivery'],
            'total' => $cartData['total']
        ]);
    }    

    public function show(MenuItem $menuItem)
    {
        return $menuItem;
    }
}
