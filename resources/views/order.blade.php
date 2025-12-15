@extends('layouts.app-layout')

@section('title', 'Order')


@guest
    @section('content')
        <div class="card mt-5">
            <div class="card-body">
                <h5 class="card-title">Please Login</h5>
                <p class="card-text">You need to login to view your order.</p>
            </div>
        </div>
    @endsection

    @section('sidebar-main')
        <div class="container-fluid d-flex flex-column vh-100 pb-5 px-4" style="overflow-y: auto; max-height: 100vh;">
            <h3 class="mt-5"><strong>Order Summary</strong></h3>
        </div>
    @endsection
@endguest

@auth
@section('content')

    {{-- @dd($orders); --}}

@if ($orders->isEmpty())
<div class="card mt-5">
    <div class="card-body">
        <h5 class="card-title">Order Not Found</h5>
        <p class="card-text">No orders found for today.</p>
    </div>
</div>
@else
    @foreach ($orders as $order)
    @php
        $statuses = [
            1 => 'Received',
            2 => 'Confirmed',
            3 => 'Preparing',
            4 => 'Delivering',
            5 => 'Delivered',
        ];

        $status = $statuses[$order->status_id] ?? 'Encountered some problems';
        $progressPercentage = min((($order->status_id - 1) / 4) * 100, 100);

    @endphp

    <div class="card mt-5">
        <div class="row">
            <div class="col-6">
                @cannot('admin')
                <div class="card-body border-end m-3">
                    <h6>Your Order is</h6>
                    <h1><strong> {{ $status }} </strong></h1>
                    <h6>Order at: {{ \Carbon\Carbon::parse($order->updated_at)->format('H:i d, m, Y') }}</h6>
                    <p class="card-text"><small class="text-body-secondary">Last updated {{ \Carbon\Carbon::parse($order->updated_at)->diffForHumans() }}</small></p>
                </div>
                @endcannot
                @can('admin')
                <div class="card-body border-end m3">
                    @foreach($order->orderItems as $item)
                    <div class="row">
                        <div class="col-6">
                            <div class="card p-3 border-0">
                                <img 
                                src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/350x350?text=Image' }}" 
                                class="card-img h-100 w-100 object-fit-cover" 
                                alt="Menu Item Image">
                            </div>
                        </div>
                        <div class="col mt-3">
                            <h6 class="card-text">{{ $item->menuItem->name }}</h6>
                            <p class="card-text">{{ $item->menuItem->price }}</p>
                            <p class="card-text">Quantity: {{ $item->quantity }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endcan
            </div>
            <div class="col-6">
                <div class="card-body m-3">                       
                    <h5 class="card-title">Order No: </h5>
                    <p class="card-text">{{ $order->id }}</p>
    
                    <h5 class="cart-title">Delivery Address: </h5>
                    <p class="card-text">{{ $order->address }}</p>

                    @can('admin')
                        <form action="{{ route('order.status', $order) }}" method="post">
                            @csrf
                            @method('PUT')
                    
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status_id">
                                    @foreach ($statuses as $id => $text)
                                        <option value="{{ $id }}" {{ $order->status_id == $id ? 'selected' : '' }}>{{ $text }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-pill">Update Status</button>
                        </form>
                    @endcan                
                </div>
            </div>
        </div>
        @cannot('admin')
        <div class="card-body">
            <h5 class="card-title mx-3"> Tracking History </h5>

            <div id="container" class="container mt-5">
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                    style="width: {{ $progressPercentage }}%;" 
                    aria-valuenow="{{ $progressPercentage }}" 
                    aria-valuemin="0" 
                    aria-valuemax="100">
               </div>
                </div>
                <div class="step-container d-flex justify-content-between"
                    style="
                        position: relative;
                        text-align: center;
                        transform: translateY(-33%);">
                    
                    @foreach ($statuses as $id => $text)
                        <div class="step-outer d-flex flex-column align-items-center">
                            @if ($order->status_id >= $id)
                                <div class="step-circle step-success">
                                    <i class="cil-check-alt text-light"></i>
                                </div>
                            @else
                                @switch($id)
                                    @case(1)
                                        <div class="step-circle">
                                            <i class="cil-clipboard"></i>
                                        </div>
                                        @break
                                    @case(2)
                                        <div class="step-circle">
                                            <i class="cil-check-circle"></i>
                                        </div>
                                        @break
                                    @case(3)
                                        <div class="step-circle">
                                            <i class="cil-cog"></i>
                                        </div>
                                        @break
                                    @case(4)
                                        <div class="step-circle">
                                            <i class="cil-truck"></i>
                                        </div>
                                        @break
                                    @case(5)
                                        <div class="step-circle">
                                            <i class="cil-location-pin"></i>
                                        </div>
                                        @break
                                    @default
                                        <div class="step-circle">
                                            <i class="cil-ban"></i>
                                        </div>
                                @endswitch
                            @endif
                            <div class="step-text">{{ $text }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endcannot
    </div>
    @endforeach
@endif

@endsection

@section('sidebar-main')
    <div class="container-fluid d-flex flex-column vh-100 pb-5 px-4" style="overflow-y: auto; max-height: 100vh;">
        @cannot('admin')
        <h3 class="mt-5"><strong>Order Summary</strong></h3>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Customer Name: </h5>
                <p class="card-text">{{ Auth::user()->name; }}</p>

                <h5 class="card-title">Customer Contact: </h5>
                <p class="card-text">012892633</p>
            </div>
        </div>
        @endcannot
        @can('admin')
            <h3 class="mt-5"><strong>Today Order</strong></h3>
        
            @if ($orders->isEmpty())
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Order Not Found</h5>
                        <p class="card-text">No orders found for today.</p>
                    </div>
                </div>
            @else
                @php
                    $total = 0;
                    $totalOrder = $orders->count(); // Count total orders
        
                    foreach ($orders as $order) {
                        foreach ($order->orderItems as $item) {
                            $total += $item->menuItem->price * $item->quantity;
                        }
                    }
                @endphp
        
                <!-- Total Amount Card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Amount: </h5>
                        <p class="card-text">{{ $total }}</p>
                    </div>
                </div>
        
                <!-- Total Orders Card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders: </h5>
                        <p class="card-text">{{ $totalOrder }}</p>
                    </div>
                </div>
            @endif
        @endcan    
    </div>
@endsection
@endauth