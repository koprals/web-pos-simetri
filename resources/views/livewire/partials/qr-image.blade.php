@if ($selectedPaymentMethod?->image)
<div class="mb-4">
    <div class="flex items-center bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
        <div class="flex items-center">
            <img src="{{ asset('storage/' . $selectedPaymentMethod->image) }}" alt="Qr Image" class="w-50 h-50 object-cover rounded-lg mr-2">
        </div>
    </div>
</div>
@endif
