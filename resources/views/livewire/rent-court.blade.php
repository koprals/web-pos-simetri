<div class="grid grid-cols-1 dark:bg-gray-900 md:grid-cols-3 gap-4">
    <!-- Left Column - Court List -->
    <div class="md:col-span-2 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <div class="mb-4 flex gap-2">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari lapangan..."
                class="w-full p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"
            >
        </div>

        <div class="flex-grow">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($courts as $court)
                    <div
                        wire:key="court-{{ $court->id }}"
                        wire:click="selectCourt({{ $court->id }})"
                        class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow cursor-pointer transition-all duration-200
                               @if($selectedCourt == $court->id) border-2 border-blue-500 dark:border-blue-400 @else hover:border hover:border-gray-300 dark:hover:border-gray-600 @endif"
                    >
                        <img
                            src="{{ $court->image_url ?? asset('images/default-court.jpg') }}"
                            alt="Court Image"
                            class="w-full h-32 object-cover rounded-lg mb-2"
                            onerror="this.src='{{ asset('images/default-court.jpg') }}'"
                        >
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $court->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-xs">Rp. {{ number_format($court->price, 0, ',', '.') }} /jam</p>

                        @if($selectedCourt == $court->id)
                            <div class="mt-3">
                                <h4 class="text-xs font-semibold mb-2 text-gray-700 dark:text-gray-300">Pilih Jam Tersedia:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($availableHours as $time)
                                        <button
                                            type="button"
                                            wire:key="time-{{ $time }}"
                                            wire:click.stop="toggleTime('{{ $time }}')"
                                            class="px-3 py-2 text-xs rounded-lg transition-all duration-200 flex items-center justify-center
                                                   @if(in_array($time, $selectedTimes))
                                                       bg-blue-500 hover:bg-blue-600 shadow-md
                                                   @else
                                                       bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-500
                                                   @endif"
                                            style="min-width: 60px;"
                                        >
                                            {{ $time }}
                                            @if(in_array($time, $selectedTimes))
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="py-4">
                {{ $courts->links() }}
            </div>
        </div>
    </div>

    <!-- Right Column - Checkout Form -->
    <div class="md:col-span-1 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form wire:submit.prevent="checkout">
            {{ $this->form }}

            @if($selectedCourt && count($selectedTimes) > 0)
                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/50" style="margin-top:1%">
                    <h4 class="font-semibold mb-3 text-blue-800 dark:text-blue-200 flex items-center">
                        Detail Booking
                    </h4>
                    <div class="space-y-2 text-sm" style="margin-top: 5%">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Lapangan:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ \App\Models\Court::find($selectedCourt)->name ?? '' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tanggal:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Jam:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ implode(', ', $selectedTimes) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-blue-100 dark:border-blue-800/50 mt-2">
                            <span class="text-gray-600 dark:text-gray-400">Total:</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <x-filament::button
                type="submit"
                class="w-full bg-red-500 mt-3 text-white py-2 rounded">Checkout</x-filament::button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized successfully');
    });
</script>
@endpush
