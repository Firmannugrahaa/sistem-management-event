<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Item Vendor: {{ $vendor->name }}
                <span class="text-sm font-normal text-gray-500 block">Event: {{ $event->event_name }}</span>
            </h2>
            <a href="{{ route('events.show', $event) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Event</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Existing Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Item Terpilih</h3>
                    @if($existingItems->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($existingItems as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $item->itemable->name ?? 'Item Deleted' }}</div>
                                            @if($item->notes)
                                                <div class="text-xs text-gray-500">{{ $item->notes }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @php
                                                $type = class_basename($item->itemable_type);
                                                echo match($type) {
                                                    'VendorProduct' => 'Layanan',
                                                    'VendorPackage' => 'Paket',
                                                    'VendorCatalogItem' => 'Katalog',
                                                    default => $type
                                                };
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            <form action="{{ route('events.vendor-items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-6 py-4 text-right font-bold">Total Keseluruhan:</td>
                                    <td class="px-6 py-4 font-bold text-indigo-600">
                                        Rp {{ number_format($existingItems->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada item yang dipilih dari vendor ini.</p>
                    @endif
                </div>
            </div>

            <!-- Add Items Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-6">Tambah Item</h3>
                    
                    <form action="{{ route('events.vendor-items.store', [$event, $vendor]) }}" method="POST">
                        @csrf
                        
                        <div x-data="{ activeTab: 'services' }">
                            <div class="border-b border-gray-200 mb-4">
                                <nav class="-mb-px flex space-x-8">
                                    <a href="#" @click.prevent="activeTab = 'services'"
                                       :class="{'border-indigo-500 text-indigo-600': activeTab === 'services', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'services'}"
                                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        Layanan (Services)
                                    </a>
                                    <a href="#" @click.prevent="activeTab = 'packages'"
                                       :class="{'border-indigo-500 text-indigo-600': activeTab === 'packages', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'packages'}"
                                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        Paket Layanan
                                    </a>
                                    <a href="#" @click.prevent="activeTab = 'catalog'"
                                       :class="{'border-indigo-500 text-indigo-600': activeTab === 'catalog', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'catalog'}"
                                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        Katalog (Inventory)
                                    </a>
                                </nav>
                            </div>

                            <!-- Services Tab -->
                            <div x-show="activeTab === 'services'" class="space-y-4">
                                @if($products->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($products as $product)
                                            @php $uid = 'p_' . $product->id; @endphp
                                            <div class="border rounded-lg p-4 hover:border-indigo-300 transition relative">
                                                <div class="flex justify-between items-start mb-2">
                                                    <h4 class="font-bold text-gray-900">{{ $product->name }}</h4>
                                                    <span class="text-xs font-semibold bg-gray-100 px-2 py-1 rounded">{{ $product->category }}</span>
                                                </div>
                                                <p class="text-sm text-gray-500 mb-2 line-clamp-2">{{ $product->description }}</p>
                                                <div class="text-indigo-600 font-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                                
                                                <div class="flex items-center gap-2 mt-2">
                                                    <input type="checkbox" name="selected_items[]" value="{{ $uid }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                    
                                                    <input type="hidden" name="items[{{ $uid }}][type]" value="product">
                                                    <input type="hidden" name="items[{{ $uid }}][id]" value="{{ $product->id }}">
                                                    <input type="hidden" name="items[{{ $uid }}][price]" value="{{ $product->price }}">
                                                    
                                                    <input type="number" name="items[{{ $uid }}][quantity]" value="1" min="1" class="w-16 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Qty">
                                                    <input type="text" name="items[{{ $uid }}][notes]" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Catatan...">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500">Vendor ini belum memiliki layanan.</p>
                                @endif
                            </div>

                            <!-- Packages Tab -->
                            <div x-show="activeTab === 'packages'" class="space-y-4" style="display: none;">
                                @if($packages->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($packages as $package)
                                            @php $uid = 'pkg_' . $package->id; @endphp
                                            <div class="border rounded-lg overflow-hidden hover:border-indigo-300 transition relative">
                                                <!-- Thumbnail -->
                                                <div class="aspect-video bg-gradient-to-br from-indigo-500 to-purple-600 relative">
                                                    @if($package->thumbnail_path)
                                                        <img src="{{ asset('storage/' . $package->thumbnail_path) }}" 
                                                             alt="{{ $package->name }}" 
                                                             class="w-full h-full object-cover">
                                                    @endif
                                                    @if($package->savings > 0)
                                                        <div class="absolute top-2 right-2">
                                                            <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">
                                                                Hemat {{ $package->savings_percentage }}%
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="p-4">
                                                    <h4 class="font-bold text-gray-900 mb-1">{{ $package->name }}</h4>
                                                    <p class="text-xs text-gray-500 mb-3">{{ $package->services->count() }} Layanan Termasuk</p>
                                                    
                                                    <div class="mb-3">
                                                        <div class="text-lg font-bold text-indigo-600">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                                        @if($package->individual_price > $package->price)
                                                            <div class="text-xs text-gray-400 line-through">
                                                                Rp {{ number_format($package->individual_price, 0, ',', '.') }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-200">
                                                        <input type="checkbox" name="selected_items[]" value="{{ $uid }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                        
                                                        <input type="hidden" name="items[{{ $uid }}][type]" value="package">
                                                        <input type="hidden" name="items[{{ $uid }}][id]" value="{{ $package->id }}">
                                                        <input type="hidden" name="items[{{ $uid }}][price]" value="{{ $package->price }}">
                                                        
                                                        <input type="number" name="items[{{ $uid }}][quantity]" value="1" min="1" class="w-16 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Qty">
                                                        <input type="text" name="items[{{ $uid }}][notes]" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Catatan...">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500">Vendor ini belum memiliki paket layanan.</p>
                                @endif
                            </div>

                            <!-- Catalog Tab -->
                            <div x-show="activeTab === 'catalog'" class="space-y-4" style="display: none;">
                                @if($catalogItems->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($catalogItems as $item)
                                            @php $uid = 'c_' . $item->id; @endphp
                                            <div class="border rounded-lg p-4 hover:border-indigo-300 transition relative {{ $item->status !== 'available' ? 'opacity-60 bg-gray-50' : '' }}">
                                                <div class="flex justify-between items-start mb-2">
                                                    <h4 class="font-bold text-gray-900">{{ $item->name }}</h4>
                                                    <span class="text-xs font-semibold {{ $item->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </div>
                                                <div class="text-indigo-600 font-bold mb-4">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                                
                                                @if($item->status === 'available')
                                                    <div class="flex items-center gap-2 mt-2">
                                                        <input type="checkbox" name="selected_items[]" value="{{ $uid }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                        
                                                        <input type="hidden" name="items[{{ $uid }}][type]" value="catalog_item">
                                                        <input type="hidden" name="items[{{ $uid }}][id]" value="{{ $item->id }}">
                                                        <input type="hidden" name="items[{{ $uid }}][price]" value="{{ $item->price }}">
                                                        
                                                        <input type="number" name="items[{{ $uid }}][quantity]" value="1" min="1" class="w-16 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Qty">
                                                        <input type="text" name="items[{{ $uid }}][notes]" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Catatan...">
                                                    </div>
                                                @else
                                                    <p class="text-xs text-red-500 italic">Item tidak tersedia.</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500">Vendor ini belum memiliki item katalog.</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                            <x-primary-button>
                                Simpan Item Terpilih
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
