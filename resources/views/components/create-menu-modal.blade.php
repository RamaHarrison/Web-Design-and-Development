<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="addItemForm" action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <x-form-input type="text" name="name" label="Item Name" placeholder="Enter item name" />
                    <div class="mb-3">
                        <label for="itemCategory" class="form-label">Category</label>
                        <select class="form-select" id="itemCategory" name="category_id">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form-input type="float" name="price" label="Price" placeholder="Enter item price" />
                    <div class="mb-3">
                        <label for="itemDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="itemDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="itemImage" class="form-label">Item Image</label>
                        <input type="file" class="form-control" id="itemImage" name="image" accept="image/*">
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="addItemForm" class="btn btn-primary">Save Item</button>
            </div>
        </div>
    </div>
</div>