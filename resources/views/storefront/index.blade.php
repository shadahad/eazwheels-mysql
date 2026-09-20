<!DOCTYPE html>
<html lang="en-GB" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EAZWHEELS | Precision Automotive Wheel Accessories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .badge-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .6; } }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased font-sans">

    <!-- 1. Topmost Dispatch Bar -->
    <div class="bg-slate-900 text-white text-xs py-2 px-4 text-center font-medium tracking-wide flex justify-center items-center gap-2">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 badge-pulse"></span>
        <span>⚡ Sameday dispatch within 3-4 hours on all UK warehouse orders placed before 2 PM GMT</span>
    </div>

    <!-- 2. Main Navigation Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ route('storefront.home') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl font-black tracking-tighter text-slate-900 group-hover:text-indigo-600 transition">
                        EAZ<span class="text-indigo-600">WHEELS</span>
                    </span>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium text-slate-600">
                    <a href="{{ route('storefront.home') }}" class="flex items-center text-indigo-600 font-semibold gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Home
                    </a>
                    
                    <!-- Products Dropdown -->
                    <div class="relative group">
                        <button class="hover:text-slate-900 flex items-center gap-1 py-2">
                            Products
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="absolute left-0 mt-0 w-56 bg-white border border-gray-100 rounded-lg shadow-xl py-2 hidden group-hover:block transition">
                            <a href="#featured" class="block px-4 py-2 text-xs hover:bg-slate-50 text-slate-700">Wheel Rims</a>
                            <a href="#featured" class="block px-4 py-2 text-xs hover:bg-slate-50 text-slate-700">Trim & Covers</a>
                            <a href="#featured" class="block px-4 py-2 text-xs hover:bg-slate-50 text-slate-700">Spoilers</a>
                            <a href="#featured" class="block px-4 py-2 text-xs hover:bg-slate-50 text-slate-700">Miscellaneous Accessories</a>
                        </div>
                    </div>

                    <a href="#size-guide" class="hover:text-slate-900">Size Guide</a>
                    <a href="#pothole-guard" class="hover:text-slate-900">Pothole-Guard™ Tech</a>
                    <a href="#reviews" class="hover:text-slate-900">Reviews</a>
                </nav>
            </div>

            <!-- Right Badge & Cart -->
            <div class="flex items-center gap-4">
                <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                    In Stock (UK Warehouse)
                </span>

                <div class="relative cursor-pointer bg-slate-100 p-2.5 rounded-full hover:bg-slate-200 transition" onclick="toggleCartModal()">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span id="cart-counter" class="absolute -top-1 -right-1 bg-indigo-600 text-white font-bold text-[10px] w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">0</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Bar Sub-Header -->
    <div class="bg-slate-100 py-3 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('storefront.home') }}" class="flex gap-2">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search by model, rim size, or accessory name..." 
                           class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                    <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">Filter</button>
                @if($query)
                    <a href="{{ route('storefront.home') }}" class="bg-gray-200 text-slate-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300 flex items-center">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- 3. Hero Section -->
    <section class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white py-20 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-indigo-400 text-xs font-bold tracking-widest uppercase">Engineered British Toughness</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mt-3 mb-6 leading-tight">
                    Unbreakable Wheel Accessories Built For UK Roads.
                </h1>
                <p class="text-slate-300 text-base leading-relaxed mb-8">
                    Shield your rims from curb impact, potholes, and salt corrosion. Custom-tolerance wheel-cups, trims, and spoilers designed for 100% factory flush fitment.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#featured" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold text-sm transition">Browse Storefront</a>
                    <a href="#size-guide" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-lg font-semibold text-sm transition border border-white/20">Find My Rim Size</a>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=800&q=80" alt="Aerodynamic Wheel Rim" class="rounded-2xl shadow-2xl border border-white/10 object-cover w-full h-[380px]">
            </div>
        </div>
    </section>

    <!-- 4. Pillar Highlights -->
    <section class="bg-white border-y border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-3">
                <div class="text-indigo-600 text-2xl font-bold mb-1">100%</div>
                <div class="text-xs uppercase tracking-wider font-bold text-slate-800">Virgin Impact-Grade ABS</div>
                <div class="text-xs text-slate-500 mt-1">Zero recycled brittle plastic</div>
            </div>
            <div class="p-3">
                <div class="text-indigo-600 text-2xl font-bold mb-1">2-Minute</div>
                <div class="text-xs uppercase tracking-wider font-bold text-slate-800">Tool-Free Installation</div>
                <div class="text-xs text-slate-500 mt-1">Direct click-to-rim snap lock</div>
            </div>
            <div class="p-3">
                <div class="text-indigo-600 text-2xl font-bold mb-1">8hr Free</div>
                <div class="text-xs uppercase tracking-wider font-bold text-slate-800">Tracked UK Dispatch</div>
                <div class="text-xs text-slate-500 mt-1">Direct from Northampton hub</div>
            </div>
            <div class="p-3">
                <div class="text-indigo-600 text-2xl font-bold mb-1">Anti-Vibe</div>
                <div class="text-xs uppercase tracking-wider font-bold text-slate-800">No Fly-Off Steel Clips</div>
                <div class="text-xs text-slate-500 mt-1">Tested to 140 MPH forces</div>
            </div>
        </div>
    </section>

    <!-- 5. Featured Vehicle Accessories (Live DB Items) -->
    <section id="featured" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Vehicle Accessories Catalog</h2>
                <p class="text-slate-500 text-sm mt-1">Real-time stock straight from our Northampton distribution warehouse.</p>
            </div>
            <span class="text-xs font-semibold text-slate-400">Total Items: {{ count($items) }}</span>
        </div>

        @if(empty($items))
            <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
                <p class="text-slate-500 text-sm">No automotive items match your current filter parameters.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($items as $item)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition flex flex-col justify-between">
                        <div>
                            <div class="relative h-48 bg-gray-100 overflow-hidden">
                                <img src="{{ $item->itemImage }}" alt="{{ $item->itemName }}" class="w-full h-full object-cover">
                                <span class="absolute top-2 right-2 bg-slate-900/80 text-white text-[11px] font-bold px-2 py-0.5 rounded">
                                    {{ $item->size }}" Rim
                                </span>
                            </div>
                            <div class="p-4">
                                <div class="text-[10px] text-slate-400 font-mono">ID: {{ substr($item->id, 0, 8) }}...</div>
                                <h3 class="font-bold text-slate-900 text-base mt-1 line-clamp-1">{{ $item->itemName }}</h3>
                                <p class="text-slate-500 text-xs mt-1.5 line-clamp-2">{{ $item->description }}</p>
                                
                                <div class="mt-4 flex items-baseline justify-between">
                                    <span class="text-lg font-black text-slate-900">£{{ number_format($item->cost, 2) }}</span>
                                    @if($item->unitsInStock > 0)
                                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                            {{ $item->unitsInStock }} in stock
                                        </span>
                                    @else
                                        <span class="text-xs font-medium text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-100">
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Purchase Footer -->
                        <div class="p-4 bg-slate-50 border-t border-gray-100">
                            @if($item->unitsInStock > 0)
                                <div class="flex gap-2 items-center">
                                    <input type="number" 
                                           id="qty-{{ $item->id }}" 
                                           min="1" 
                                           max="{{ $item->unitsInStock }}" 
                                           value="1" 
                                           class="w-16 px-2 py-1.5 border border-gray-300 rounded text-center text-sm font-semibold focus:ring-1 focus:ring-indigo-500">
                                    <button onclick="addToBasket('{{ $item->id }}', '{{ addslashes($item->itemName) }}', {{ $item->cost }}, {{ $item->unitsInStock }})"
                                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1.5 px-3 rounded text-xs transition">
                                        Add To Basket
                                    </button>
                                </div>
                            @else
                                <div class="flex gap-2 items-center">
                                    <input type="number" disabled value="0" class="w-16 px-2 py-1.5 bg-gray-100 border border-gray-200 rounded text-center text-sm text-gray-400 cursor-not-allowed">
                                    <button disabled class="flex-1 bg-gray-300 text-gray-500 font-semibold py-1.5 px-3 rounded text-xs cursor-not-allowed">
                                        Unavailable
                                    </button>
                                </div>
                            @endif

                            <div class="mt-2 text-center">
                                <a href="mailto:orders@eazwheels.co.uk?subject=Item Inquiry: {{ urlencode($item->itemName) }} (Ref: {{ $item->id }})" 
                                   class="text-[11px] text-slate-500 hover:text-indigo-600 underline">
                                    Enquire about this vehicle fitment
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- 6. Customer Favorite Vehicle Collections -->
    <section class="bg-slate-100 py-16 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-black text-slate-900 mb-8 text-center">Customer Favorite Vehicle Collections</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-indigo-600 tracking-wider">MODERN EV & HYBRID</span>
                        <h3 class="text-lg font-bold text-slate-900 mt-1">Aero-Efficiency Wheel Covers</h3>
                        <p class="text-slate-600 text-xs mt-2">Increases battery range up to 4.2% on highway runs by reducing drag coefficient over factory rims.</p>
                    </div>
                    <div class="mt-6 text-xs text-indigo-700 font-semibold">Compatible with 18"-20" OEM alloys &rarr;</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-indigo-600 tracking-wider">FLEET & COMMERCIAL</span>
                        <h3 class="text-lg font-bold text-slate-900 mt-1">Heavy-Duty Steel Rim Protectors</h3>
                        <p class="text-slate-600 text-xs mt-2">Engineered specifically for transit vans and delivery vehicles subject to frequent urban curb contact.</p>
                    </div>
                    <div class="mt-6 text-xs text-indigo-700 font-semibold">Available in 15" and 16" heavy gauge &rarr;</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-indigo-600 tracking-wider">MOTORSPORT STYLING</span>
                        <h3 class="text-lg font-bold text-slate-900 mt-1">Gloss Black Aero Fin Spoilers</h3>
                        <p class="text-slate-600 text-xs mt-2">Direct boot-lid and tailgate lip spoilers with genuine 3M structural adhesive backing pre-applied.</p>
                    </div>
                    <div class="mt-6 text-xs text-indigo-700 font-semibold">Universal contour fits &rarr;</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Need a Specific Style or Single Replacement Cup? -->
    <section class="py-14 bg-indigo-900 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold">Lost A Single Wheel Cup To A Pothole?</h2>
            <p class="text-indigo-200 text-sm mt-2 max-w-xl mx-auto">
                No need to buy a full set of 4. We stock dedicated single-piece replacements for all active catalog items.
            </p>
            <a href="mailto:support@eazwheels.co.uk?subject=Single Replacement Request" class="inline-block mt-6 bg-white text-indigo-900 font-bold px-6 py-2.5 rounded-lg text-sm hover:bg-slate-100 transition">
                Order Single Replacement Piece
            </a>
        </div>
    </section>

    <!-- 8. How To Find Your Exact Wheel Cup Size in 10 Seconds -->
    <section id="size-guide" class="py-16 max-w-5xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <h2 class="text-2xl font-black text-slate-900 text-center mb-6">How To Find Your Exact Wheel Cup Size in 10 Seconds</h2>
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shrink-0">1</div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Locate Tyre Sidewall Marking</h4>
                            <p class="text-slate-500 text-xs mt-1">Inspect the raised lettering on the rubber sidewall of your tire (e.g. <strong>205/55 R16</strong>).</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shrink-0">2</div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Read the "R" Digit</h4>
                            <p class="text-slate-500 text-xs mt-1">The number following the letter 'R' indicates your wheel diameter in inches (R16 = 16 Inch).</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shrink-0">3</div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Order Exact Match</h4>
                            <p class="text-slate-500 text-xs mt-1">Select the identical inch rating from our catalog. Dual-tension steel rings ensure precision fitment.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-900 text-indigo-300 p-6 rounded-xl font-mono text-center">
                    <span class="text-xs text-slate-400 block mb-2">TYRE SIDEWALL DECODER</span>
                    <span class="text-xl sm:text-2xl font-bold tracking-widest text-white">205 / 55 <span class="text-indigo-400 underline font-black">R 16</span></span>
                    <span class="text-xs text-emerald-400 block mt-2">↑ Matches 16" EazWheels Range</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Engineering Specs (Pothole-Guard™ Tech) -->
    <section id="pothole-guard" class="bg-slate-50 border-y border-gray-200 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-indigo-600 font-bold text-xs uppercase tracking-widest">Patented Design</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Pothole-Guard™ Engineering</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl border border-gray-200">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-4 font-black">01</div>
                    <h3 class="font-bold text-slate-900 text-base mb-2">Dual-Tension Steel Rings</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Adjustable perimeter spring-steel retention ring guarantees maximum friction clamp against harsh UK road impacts.</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-200">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-4 font-black">02</div>
                    <h3 class="font-bold text-slate-900 text-base mb-2">Road Salt & UV Resistant</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Triple-coated automotive lacquer finish prevents yellowing, flaking, or brittleness induced by winter council gritting.</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-200">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-4 font-black">03</div>
                    <h3 class="font-bold text-slate-900 text-base mb-2">Zero Tools Required</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Engineered snap-in teeth distribute perimeter load evenly. Align valve stem notch and push to click firmly into place.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. Reviews: Trusted By Drivers Nationwide -->
    <section id="reviews" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-black text-slate-900 text-center mb-10">Trusted By Drivers Nationwide</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-amber-400 text-sm mb-2">★★★★★</div>
                <p class="text-slate-700 text-xs italic leading-relaxed">"Hit a horrific crater on the M1 near Watford. Thought the rim cover would fly right off, but the steel clip held solid. Superb quality."</p>
                <div class="mt-4 font-bold text-xs text-slate-900">David G. — St Albans</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-amber-400 text-sm mb-2">★★★★★</div>
                <p class="text-slate-700 text-xs italic leading-relaxed">"Arrived in Yorkshire the next morning via DPD. Fitted all four onto my Ford transit steelies in less than 5 minutes."</p>
                <div class="mt-4 font-bold text-xs text-slate-900">Craig M. — Leeds</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-amber-400 text-sm mb-2">★★★★★</div>
                <p class="text-slate-700 text-xs italic leading-relaxed">"Replaced dull factory hubcaps with the Matte Carbon style. The car genuinely looks 5 years newer."</p>
                <div class="mt-4 font-bold text-xs text-slate-900">Eleanor P. — Edinburgh</div>
            </div>
        </div>
    </section>

    <!-- 11. First Order Special -->
    <section class="bg-gradient-to-r from-indigo-700 to-indigo-900 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <span class="bg-white/20 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Driver Welcome Offer</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold mt-3">Take 10% Off Your First UK Accessory Order</h2>
            <p class="text-indigo-200 text-xs mt-2">Use voucher code <code class="bg-black/30 px-2 py-0.5 rounded text-white font-mono">EAZFIRST10</code> at checkout.</p>
        </div>
    </section>

    <!-- 12. Bottom Quick Links -->
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
            <div>
                <h4 class="text-white text-xs font-bold tracking-wider uppercase mb-4">Fitment Categories</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#featured" class="hover:text-white">Wheel Trims 14" to 17"</a></li>
                    <li><a href="#featured" class="hover:text-white">Aero Covers for EVs</a></li>
                    <li><a href="#featured" class="hover:text-white">High-Downforce Boot Spoilers</a></li>
                    <li><a href="#featured" class="hover:text-white">Replacement Retention Rings</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white text-xs font-bold tracking-wider uppercase mb-4">Technical Help</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#size-guide" class="hover:text-white">Sidewall Sizing Chart</a></li>
                    <li><a href="#pothole-guard" class="hover:text-white">Pothole-Guard™ Safety Tests</a></li>
                    <li><a href="mailto:support@eazwheels.co.uk" class="hover:text-white">Commercial Fleet Enquiries</a></li>
                    <li><a href="mailto:returns@eazwheels.co.uk" class="hover:text-white">30-Day UK Return Guarantee</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white text-xs font-bold tracking-wider uppercase mb-4">Fulfillment Hub</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    EazWheels UK Distribution<br>
                    Brackmills Logistics Park<br>
                    Northampton, NN4 7PW<br>
                    United Kingdom
                </p>
            </div>
            <div>
                <h4 class="text-white text-xs font-bold tracking-wider uppercase mb-4">Administration</h4>
                <p class="text-xs mb-3">Authorize catalog updates via cryptographically verified access key.</p>
                <a href="/admin" class="inline-block text-xs bg-slate-800 hover:bg-slate-700 text-indigo-400 px-3 py-1.5 rounded border border-slate-700">
                    Terminal Admin Login &rarr;
                </a>
            </div>
        </div>

        <!-- 13. Copyright & Payment Options -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <p class="text-slate-500">
                &copy; {{ date('Y') }} EAZWHEELS UK LTD. All rights reserved. Registered in England & Wales.
            </p>
            <div class="flex items-center space-x-3 text-slate-400">
                <span class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Secure Card & Direct Checkout:</span>
                <span class="bg-slate-800 px-2 py-1 rounded text-white font-bold text-[10px]">VISA</span>
                <span class="bg-slate-800 px-2 py-1 rounded text-white font-bold text-[10px]">MASTERCARD</span>
                <span class="bg-slate-800 px-2 py-1 rounded text-white font-bold text-[10px]">AMEX</span>
                <span class="bg-slate-800 px-2 py-1 rounded text-indigo-400 font-bold text-[10px]">APPLE PAY</span>
                <span class="bg-slate-800 px-2 py-1 rounded text-emerald-400 font-bold text-[10px]">CLEARPAY</span>
            </div>
        </div>
    </footer>

    <!-- Cart Modal (Alpine/Vanilla JS) -->
    <div id="cart-modal" class="fixed inset-0 bg-black/60 z-50 hidden flex justify-end">
        <div class="bg-white w-full max-w-md h-full p-6 flex flex-col justify-between shadow-2xl">
            <div>
                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-slate-900">Your Basket</h3>
                    <button onclick="toggleCartModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>
                <div id="cart-items" class="py-4 space-y-3 overflow-y-auto max-h-[60vh]">
                    <!-- Dynamically populated -->
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between font-bold text-slate-900 text-base mb-4">
                    <span>Subtotal:</span>
                    <span id="cart-total">£0.00</span>
                </div>
                <button onclick="simulateCheckout()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg text-sm transition">
                    Checkout With Debit / Credit Card
                </button>
            </div>
        </div>
    </div>

    <script>
        let basket = [];

        function addToBasket(id, name, cost, maxStock) {
            const qtyInput = document.getElementById('qty-' + id);
            const qty = parseInt(qtyInput ? qtyInput.value : 1);

            if (qty <= 0 || qty > maxStock) {
                alert('Requested quantity exceeds available stock.');
                return;
            }

            const existingIndex = basket.findIndex(i => i.id === id);
            if (existingIndex > -1) {
                const newQty = basket[existingIndex].qty + qty;
                if (newQty > maxStock) {
                    alert('Cannot add more than ' + maxStock + ' units in total.');
                    return;
                }
                basket[existingIndex].qty = newQty;
            } else {
                basket.push({ id, name, cost, qty, maxStock });
            }

            updateBasketUI();
            toggleCartModal(true);
        }

        function updateBasketUI() {
            const count = basket.reduce((acc, curr) => acc + curr.qty, 0);
            const total = basket.reduce((acc, curr) => acc + (curr.cost * curr.qty), 0);
            document.getElementById('cart-counter').innerText = count;
            document.getElementById('cart-total').innerText = '£' + total.toFixed(2);

            const container = document.getElementById('cart-items');
            if (basket.length === 0) {
                container.innerHTML = '<p class="text-xs text-slate-400 text-center py-8">Your basket is currently empty.</p>';
                return;
            }

            container.innerHTML = basket.map(item => `
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg border border-gray-100">
                    <div>
                        <div class="font-bold text-xs text-slate-900">${item.name}</div>
                        <div class="text-[11px] text-slate-500">£${item.cost.toFixed(2)} × ${item.qty}</div>
                    </div>
                    <div class="text-xs font-bold text-slate-900">£${(item.cost * item.qty).toFixed(2)}</div>
                </div>
            `).join('');
        }

        function toggleCartModal(forceOpen = false) {
            const modal = document.getElementById('cart-modal');
            if (forceOpen) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.toggle('hidden');
            }
        }

        function simulateCheckout() {
            if (basket.length === 0) {
                alert('Your basket is empty.');
                return;
            }
            alert('Proceeding to Stripe/Card Payment Gateway for £' + document.getElementById('cart-total').innerText);
        }
    </script>
</body>
</html>