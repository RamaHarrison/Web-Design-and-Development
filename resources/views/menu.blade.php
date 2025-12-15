@extends('layouts.app-layout')

@section('title', 'Home')

@section('content')

@if(session('error'))
<div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

@if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@error('name')
<div class="alert alert-danger mt-3">{{ $message }}</div>
@enderror

@error('price')
<div class="alert alert-danger mt-3">{{ $message }}</div>
@enderror

@error('category_id')
<div class="alert alert-danger mt-3">{{ $message }}</div>
@enderror


{{-- @dd($cartItems) --}}
{{-- @dd($categories) --}}
{{-- @dd($menuItems) --}}

<div class="row g-3 mt-1">
    <div class="col btn-group" role="group" aria-label="Category toggle button group">
        <input type="radio" class="btn-check" name="category" id="all" value="all" autocomplete="off" checked>
        <label class="btn btn-outline-primary rounded-pill" for="all">All</label>
    </div>
    
    @foreach ($categories as $category)

    <div class="col btn-group" role="group" aria-label="Category toggle button group">
        <input type="radio" class="btn-check" name="category" id="{{ $category->name }}" value="{{ $category->id }}" autocomplete="off">
        <label class="btn btn-outline-primary rounded-pill" for="{{ $category->name }}">{{ $category->name }}</label>
    </div>

    @endforeach
</div>

<h1 class="my-3">Menu</h1>

@can('admin')
    <div class="d-flex justify-content-start">
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                Add Category
            </button>
        </div>
    
        @include('components.create-category-modal')
        <div class="ms-3 mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                Add Item
            </button>
        </div>
    </div>

    <!-- Add Item Modal -->
    @include('components.create-menu-modal')
@endcan

<div class="row justify-content-center g-3">
    @foreach ($menuItems as $item)
    @can('add-to-cart')
    <div class="col-5 menu-item" data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->name) }}"> 
    @endcan
    @can('admin')
    <div class="col-4 menu-item" data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->name) }}">
    @endcan
    @guest
    <div class="col-4 menu-item" data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->name) }}">
    @endguest

        @cannot('admin')
        <form class="m-0" action="{{ route('createCartItem') }}" method="post">
            @csrf
            @auth
            <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
            <input type="hidden" name="cart_id" value="{{ $cart->id }}">
            @endauth
        @endcannot

            <div class="card h-100 shadow-sm">
                <div class="row p-3">
                    {{-- Left column --}}
                    <div class="col-4">
                        <div class="card h-100 border-0">
                            <img 
                                src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/350x350?text=Image' }}" 
                                class="card-img h-100 w-100 object-fit-cover" 
                                alt="Menu Item Image">
                            @cannot('admin')
                            <div class="pt-2 input-group w-auto justify-content-center align-items-center">
                                <input type="button" value="-" class="button-minus border rounded-circle icon-shape icon-sm" data-field="quantity">
                                <input type="number" step="1" max="10" value="1" name="quantity" class="quantity-field border-0 mx-1 text-center w-25">
                                <input type="button" value="+" class="button-plus border rounded-circle icon-shape icon-sm" data-field="quantity">
                            </div>
                            @endcannot
                        </div>
                    </div>
            
                    {{-- Right column --}}
                    <div class="col-8">
                        <div class="card border-0 d-flex flex-column">
                            <div class="card-body p-0 d-flex flex-column">
                                <!-- Content -->
                                <div class="mb-3">
                                    <h5 class="card-title d-flex justify-content-between align-items-center">
                                        {{ $item->name }}
                                        <span class="text-primary fw-bold">{{ $item->price }}</span>
                                    </h5>
                                    <p class="card-text m-0">{{ $item->category->name }}</p>
                                    <p class="card-text text-muted" style="
                                        display: -webkit-box;
                                        -webkit-line-clamp: 3;
                                        -webkit-box-orient: vertical;
                                        overflow: hidden;
                                        min-height: 4.5rem;
                                    ">
                                        {{ $item->description }}
                                    </p>
                                </div>
                            </div>
                
                            {{-- Size selector --}}
                            @cannot('admin')
                            <div class="row align-items-center mt-auto">
                                <h6 class="col-auto fw-bold m-0">Size:</h6>
                                <div class="col d-flex justify-content-center">
                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="size_id" id="size_small_{{ $item->id }}" value="1" checked>
                                        <label class="btn btn-outline-secondary rounded-pill" for="size_small_{{ $item->id }}">Small</label>
                                    </div>
                                    <div class="btn-group ms-3" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="size_id" id="size_large_{{ $item->id }}" value="2">
                                        <label class="btn btn-outline-secondary rounded-pill" for="size_large_{{ $item->id }}">Large</label>
                                    </div>
                                </div>
                            </div>
                            <x-button-pill class="mt-3 btn-primary w-100" type="submit">Add to Cart</x-button-pill>
                            @endcannot
                
                            @can('admin')
                            <div class="d-flex justify-content-end mt-auto">
                                <div class="dropdown">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#editItemModal" onclick="populateEditModal({{ $item }})">
                                                <i class="cil-pencil"></i>
                                                Edit
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-danger" type="button" onclick="deleteItem({{ $item->id }})">
                                                <i class="cil-trash"></i>
                                                Delete
                                            </button>
                                            {{-- <form action="{{ route('menu.destroy', $item->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="cil-trash"></i>
                                                    Delete
                                                </button>
                                            </form> --}}
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @include('components.edit-menu-modal')
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

        @cannot('admin')
        </form>
        @endcannot

    </div>
    @endforeach
</div>
@endsection

@auth
@cannot('admin')
@section('sidebar-main')
<div class="container-fluid d-flex flex-column vh-100 pb-5 px-4" style="overflow-y: auto; max-height: 100vh;">
    @can('add-to-cart')
    <form id="cart-form" action="{{ route('order.store') }}" method="post">
        @csrf
        {{-- Header --}}
        <div class="row mt-5 align-items-center">
            <h3 class="col"><strong>Order No.</strong></h3>
            <div class="col text-end pe-3">
                <h5>{{ $cart->id }}</h5>
            </div>    
        </div>
        <input type="hidden" name="cart_id" value="{{ $cart->id }}">
        
        
        {{-- Option --}}
        <div class="row g-3 mt-3">
            <div class="col btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="option" id="delivery" value="1" autocomplete="off" checked>
                <label class="btn btn-outline-primary rounded-pill" for="delivery">Delivery</label>
            </div>
            
            <div class="col btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="option" id="pick-up" value="2" autocomplete="off">
                <label class="btn btn-outline-primary rounded-pill" for="pick-up">Pick Up</label>
            </div>
        </div>
        
        <div class="row g-3 mt-1">
            <div class="col" id="address-container">
                <x-form-input type="text" name="address" label="Address" placeholder="Enter your address" />
            </div>
        </div>
        
        {{-- Cart items --}}
        <div class="row my-3">
            <div class="col-12">
                <div class="card h-100">
                    @if(count($cartItems) == 0)
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <h6>Cart is empty</h6>
                            </div>
                        </div>
                    @endif
                    @foreach ($cartItems as $cartItem)
                        <input type="hidden" name="menuItem_ids[]" value="{{ $cartItem->menu_item_id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6><strong>{{ $cartItem->name }}</strong></h6>
                                <button type="button" class="btn-close" aria-label="Remove Item" onclick="removeItem({{ $cartItem->id }})"></button>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6>{{ $cartItem->size_id == 1 ? 'Small' : 'Large' }}</h6>
                                <input type="hidden" name="cart_item_sizes[]" value="{{ $cartItem->size_id }}">
                                <span>{{ number_format($cartItem->price, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Quantity -->
                                <x-increment-decrement-onclick id="{{ $cartItem->id }}" price="{{ $cartItem->price }}" quantity="{{ $cartItem->quantity }}" />
                                
                                <!-- Multiplied Amount -->
                                <span class="text-primary" id="item-total-{{ $cartItem->id }}">
                                    {{ number_format($cartItem->price * $cartItem->quantity, 2) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        {{-- Total --}}
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span id="subtotal">{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tax (10%)</span>
                        <span id="tax">{{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Delivery</span>
                        <span id="delivery">{{ number_format($delivery, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <span id="total">{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Payment Method</span>
                        <select name="payment_method" class="form-select">
                            <option value="1">QR</option>
                            <option value="2">Pay Later</option>
                        </select>
                    </div>
                    <div class="card mt-3">
                        <img src="https://via.placeholder.com/350x350?text=QR" class="card-img h-100 w-100 object-fit-cover">
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="total" id="hidden-total" value="{{ number_format($total, 2) }}">

        {{-- Checkout --}}
        <x-button-pill class="btn-primary btn-lg my-3" type="submit">Checkout</x-button-pill>
    </form>
    @endcan
</div>
@endsection
@endcannot
@endauth

@section('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function () {

    const deliveryRadio = document.getElementById('delivery');
    const pickupRadio = document.getElementById('pick-up');
    const addressContainer = document.getElementById('address-container');
    const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
    const imageCard = document.querySelector('.card.mt-3');

    function toggleAddressInput() {
        if (deliveryRadio.checked) {
            addressContainer.style.display = 'block';
        } else {
            addressContainer.style.display = 'none';
        }
    }

    function toggleImageCard() {
        if (paymentMethodSelect.value === "1") {
            imageCard.style.display = 'block';
        } else {
            imageCard.style.display = 'none';
        }
    }

    deliveryRadio.addEventListener('change', toggleAddressInput);
    pickupRadio.addEventListener('change', toggleAddressInput);
    paymentMethodSelect.addEventListener('change', toggleImageCard);

    toggleAddressInput();
    toggleImageCard();
    });

    function populateEditModal(menuItem) {
        const editForm = document.getElementById('editItemForm');
        editForm.action = `{{ route('menu.update', ':id') }}`.replace(':id', menuItem.id);

        document.getElementById('editName').value = menuItem.name;
        document.getElementById('editPrice').value = menuItem.price;
        document.getElementById('editCategory').value = menuItem.category_id;
        document.getElementById('editDescription').value = menuItem.description;

        const imagePreview = document.getElementById('editImagePreview');
        if (menuItem.image) {
            imagePreview.src = `/storage/${menuItem.image}`;
            imagePreview.style.display = 'block';
        } else {
            imagePreview.style.display = 'none';
        }
    }

    function deleteItem(itemId) {
        if (confirm('Are you sure you want to delete this item?')) {
            const url = '{{ route('menu.destroy', ':id') }}'.replace(':id', itemId);

            try {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'text/plain',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        alert('Item deleted successfully.');
                        location.reload();
                    } else {
                        return response.text().then(err => {
                            console.error('Error response:', err);
                            alert('An error occurred. Please try again.');
                        });
                    }
                })
                .catch(error => {
                    console.error('Network or Fetch Error:', error);
                    alert('A network error occurred. Please try again.');
                });
            } catch (error) {
                console.error('Unexpected Error:', error);
                alert('An unexpected error occurred. Please try again.');
            }
        }
    }

    function filterMenuItems() {
        const searchValue = document.getElementById('search').value.toLowerCase();
        const selectedCategory = document.querySelector('input[name="category"]:checked').value;
        const menuItems = document.querySelectorAll('.menu-item');

        menuItems.forEach((item) => {
            const category = item.getAttribute('data-category');
            const name = item.getAttribute('data-name');
            
            if ((selectedCategory === 'all' || category === selectedCategory) &&
                (name.includes(searchValue) || searchValue === '')) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    document.querySelectorAll('input[name="category"]').forEach((radio) => {
        radio.addEventListener('change', filterMenuItems);
    });

    document.querySelectorAll('input[name="category"]').forEach((radio) => {
        radio.addEventListener('change', function () {
            const selectedCategory = this.value;
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach((item) => {
                if (selectedCategory === 'all' || item.getAttribute('data-category') === selectedCategory) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

        const deliveryRadio = document.getElementById('delivery');
        const pickupRadio = document.getElementById('pick-up');
        const addressContainer = document.getElementById('address-container');

        function toggleAddressInput() {
            if (deliveryRadio.checked) {
                addressContainer.style.display = 'block';
            } else {
                addressContainer.style.display = 'none';
            }
        }

        deliveryRadio.addEventListener('change', toggleAddressInput);
        pickupRadio.addEventListener('change', toggleAddressInput);

        toggleAddressInput();
    });

    function removeItem(id) {
        const url = '{{ route('destroyCartItem', ':id') }}'.replace(':id', id);

        try {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'text/plain',
                },
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    return response.text().then(err => {
                        console.error('Error response:', err);
                        alert('An error occurred. Please try again.');
                    });
                }
            })
            .catch(error => {
                console.error('Network or Fetch Error:', error);
                alert('A network error occurred. Please try again.');
            });
        } catch (error) {
            console.error('Unexpected Error:', error);
            alert('An unexpected error occurred. Please try again.');
        }
    }

    function adjustQuantity(itemId, change) {
        const inputField = document.getElementById(`quantity-${itemId}`);

        if (!inputField) {
            console.error(`Input field for item ${itemId} not found.`);
            return;
        }

        let currentQuantity = parseInt(inputField.value, 10);
        if (isNaN(currentQuantity)) currentQuantity = 1;

        const newQuantity = Math.max(currentQuantity + change, 1);

        inputField.value = newQuantity;

        updateAmount(itemId, parseFloat(inputField.getAttribute('data-item-price')));
    }

    function updateAmount(itemId, itemPrice) {
        if (isNaN(itemPrice)) {
            console.error(`Invalid item price for item ${itemId}`);
            return;
        }

        const inputField = document.getElementById(`quantity-${itemId}`);
        const quantity = parseInt(inputField.value, 10);

        if (isNaN(quantity) || quantity < 1) {
            console.error(`Invalid quantity for item ${itemId}`);
            return;
        }

        const itemTotalElement = document.getElementById(`item-total-${itemId}`);
        if (itemTotalElement) {
            itemTotalElement.textContent = (itemPrice * quantity).toFixed(2);
        }

        const hiddenTotalInput = document.getElementById(`hidden-total-${itemId}`);
        if (hiddenTotalInput) {
            hiddenTotalInput.value = (itemPrice * quantity).toFixed(2);
        }

        updateCartTotals();
    }

    function updateCartTotals() {
        let subtotal = 0;
        const itemInputs = document.querySelectorAll('.num');

        itemInputs.forEach(input => {
            const quantity = parseInt(input.value, 10);
            const price = parseFloat(input.getAttribute('data-item-price'));

            if (!isNaN(quantity) && !isNaN(price)) {
                subtotal += quantity * price;
            }
        });

        const taxRate = 0.1; // 10% tax
        const tax = subtotal * taxRate;
        const deliveryFee = 5.0; // Flat delivery fee

        const total = subtotal + tax + deliveryFee;

        document.getElementById('subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('tax').textContent = tax.toFixed(2);
        document.getElementById('delivery').textContent = deliveryFee.toFixed(2);
        document.getElementById('total').textContent = total.toFixed(2);

        const hiddenTotalInput = document.getElementById('hidden-total');
        if (hiddenTotalInput) {
            hiddenTotalInput.value = total.toFixed(2);
        }
    }

</script>
@endsection