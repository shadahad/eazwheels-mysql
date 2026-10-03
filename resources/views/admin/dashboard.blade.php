<!DOCTYPE html>
<html lang="en-GB" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAZWHEELS | Catalog Control Engine</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen">
    <nav class="bg-slate-900 text-white px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <span class="text-xl font-black text-indigo-400">EAZWHEELS</span>
            <span class="text-xs font-mono bg-slate-800 px-2 py-1 rounded text-slate-300 border border-slate-700">Internal Raw-SQL Engine</span>
        </div>
        <div class="flex items-center gap-4 text-xs">
            <span class="text-emerald-400 font-mono">● AUTH_KEY_VERIFIED</span>
            <a href="{{ route('storefront.home') }}" class="text-slate-300 hover:text-white underline">View Public Storefront</a>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="text-slate-300 hover:text-white">
                Logout
            </button>
        </form>
    </nav>

    <main class="max-w-7xl mx-auto p-6 sm:p-8">
        @if(session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Form: Add New Item -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 lg:col-span-1 h-fit">
                <h2 class="text-base font-bold text-slate-900 mb-1">Catalog New Automotive Item</h2>
                <p class="text-xs text-slate-500 mb-4">Injects directly into MySQL using pure parameterized PDO.</p>

                <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="admin_key" value="{{ $adminKey }}">

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Item Name</label>
                        <input type="text" name="itemName" required value="{{ old('itemName') }}"
                               placeholder="e.g. Apex 16-Inch Carbon Rim Trims (Set of 4)"
                               class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Item Image (Upload from Computer)</label>
                        <input type="file" name="itemImage" accept="image/*" required
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border rounded-lg bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Cost (GBP £)</label>
                            <input type="number" step="0.01" min="0.01" name="cost" required value="{{ old('cost') }}"
                                   placeholder="49.99"
                                   class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Size (Inches)</label>
                            <input type="number" step="0.5" min="1" max="50" name="size" required value="{{ old('size') }}"
                                   placeholder="16"
                                   class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Units In Stock</label>
                        <input type="number" min="0" name="unitsInStock" required value="{{ old('unitsInStock', 0) }}"
                               class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="3" required placeholder="Impact grade ABS with dual-tension clips..."
                                  class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg text-xs transition">
                        Insert Item (Raw SQL)
                    </button>
                </form>
            </div>

            <!-- Right Table: Active Inventory & Stock Updates -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 lg:col-span-2 overflow-hidden flex flex-col justify-between">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-base font-bold text-slate-900">Live MySQL Catalog Store</h2>
                    <p class="text-xs text-slate-500">Direct inventory mutating with real-time Redis cache invalidation.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-200 text-slate-600 font-semibold">
                                <th class="p-3">UUID</th>
                                <th class="p-3">Item</th>
                                <th class="p-3">Cost</th>
                                <th class="p-3">Size</th>
                                <th class="p-3">Stock</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($items as $item)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-3 font-mono text-[11px] text-slate-400">{{ substr($item->id, 0, 8) }}...</td>
                                    <td class="p-3 font-semibold text-slate-900">{{ $item->itemName }}</td>
                                    <td class="p-3">£{{ number_format($item->cost, 2) }}</td>
                                    <td class="p-3">{{ $item->size }}"</td>
                                    <td class="p-3">
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold {{ $item->unitsInStock > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $item->unitsInStock }} in stock
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <button 
                                            type="button" 
                                            onclick="openEditModal({{ json_encode($item) }})" 
                                            class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-indigo-600 hover:text-white transition">
                                            Edit Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400 text-xs">No records stored in items table.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50 border-t border-gray-100 text-[11px] text-slate-500 flex justify-between">
                    <span>Database Engine: <strong>InnoDB (MySQL)</strong></span>
                    <span>Cache Layer: <strong>Redis phpredis</strong></span>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for Editing All 6 Details -->
    <div id="editModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-lg w-full p-6 relative max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 border-b pb-3">
                <h3 class="text-base font-bold text-slate-900">Edit Accessory Details</h3>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="admin_key" value="{{ $adminKey }}">

                <!-- 1. Item Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Item Name</label>
                    <input type="text" id="edit_itemName" name="itemName" required
                           class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">
                </div>

                <!-- 2. Item Image -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Item Image (Leave empty to keep existing)</label>
                    <input type="file" name="itemImage" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border rounded-lg bg-white">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <!-- 3. Cost -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Cost (GBP £)</label>
                        <input type="number" step="0.01" min="0.01" id="edit_cost" name="cost" required
                               class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <!-- 4. Size -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Size (Inches)</label>
                        <input type="number" step="0.5" min="1" max="50" id="edit_size" name="size" required
                               class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- 5. Units In Stock -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Units In Stock</label>
                    <input type="number" min="0" id="edit_unitsInStock" name="unitsInStock" required
                           class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500">
                </div>

                <!-- 6. Description -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                    <textarea id="edit_description" name="description" rows="3" required
                              class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-lg text-xs transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(item) {
            const form = document.getElementById('editForm');
            // Dynamically set form action using the item ID
            form.action = `/admin/items/${item.id}`;

            // Populate form values
            document.getElementById('edit_itemName').value = item.itemName ?? '';
            document.getElementById('edit_cost').value = item.cost ?? '';
            document.getElementById('edit_size').value = item.size ?? '';
            document.getElementById('edit_unitsInStock').value = item.unitsInStock ?? 0;
            document.getElementById('edit_description').value = item.description ?? '';

            // Show modal
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>