<div id="increment-decrement-{{ $id }}" class="increment-decrement-onclick shadow-sm">
    <button type="button" class="minus btn btn-light" onclick="adjustQuantity({{ $id }}, -1)">-</button>
    <input type="number" 
           id="quantity-{{ $id }}" 
           class="num form-control text-center" 
           name="quantities[]" 
           value="{{ $quantity }}" 
           min="1" 
           data-item-id="{{ $id }}" 
           data-item-price="{{ $price }}" 
           readonly>
    <button type="button" class="plus btn btn-light" onclick="adjustQuantity({{ $id }}, 1)">+</button>
</div>
<input type="hidden" name="item_totals[]" id="hidden-total-{{ $id }}" value="{{ $price * $quantity }}">