<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\MenuItem;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class adminController extends Controller
{
    public function index()
    {
        return app(menuItemController::class)->index();
        // return MenuItem::all();
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        if (Categories::where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'Category already exists');
        }

        $category = new Categories([
            'name' => $request->name,
        ]);

        try {
            $category->save();
            return redirect()->route('admin.dashboard')->with('success', 'Category added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->notValid) {
            return redirect()->back()->with('error', 'Invalid input');
        }

        if (MenuItem::where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'Menu item already exists');
        }

        $imagePath = $request->hasFile('image') 
        ? $request->file('image')->store('menu_images', 'public') 
        : null;

        $menuItem = new MenuItem([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        try {
            $menuItem->save();
            return redirect()->route('admin.dashboard')->with('success', 'Menu item added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'description' => 'nullable|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ];
    
        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }

            $updateData['image'] = $request->file('image')->store('menu_images', 'public');
        }
    
        $menuItem->update($updateData);
    
        return redirect()->route('admin.dashboard')->with('success', 'Menu item updated successfully');
    }    

    public function destroy($id) {
        
        try {
            $menuItem = MenuItem::findOrFail($id);
    
            $menuItem->delete();
    
            return response('Menu item removed successfully', 200);
        } catch (ModelNotFoundException $e) {
            return response($e->getMessage(), 404);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    public function order()
    {
        $order = Order::with(['orderItems.menuItem'])
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order', [
            'orders' => $order,
        ]);
    }

    public function history()
    {
        $order = Order::with(['orderItems.menuItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('history', [
            'orders' => $order,
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        // dd($request->all());

        // $request->validate([
        //     'status_id' => 'required|integer|exists:statuses,id',
        // ]);

        $order->status_id = $request->status_id;
        $order->save();

        return redirect()->route('admin.order')->with('success', 'Order status updated successfully');
    }
}
