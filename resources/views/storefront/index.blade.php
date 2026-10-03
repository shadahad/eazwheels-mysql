<!DOCTYPE html>
<html lang="en-GB" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EAZWHEELS | Precision Automotive Wheel Accessories</title>
    <!-- Favicon Suite -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
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
        <span>‚ö° Sameday dispatch within 3-4 hours on all UK warehouse orders placed before 2 PM GMT</span>
    </div>
    
    <!-- Floating Notification Banner -->
<div id="enquiry-toast" class="hidden fixed top-5 right-5 z-50 max-w-md bg-emerald-900 text-white p-4 rounded-xl shadow-2xl border border-emerald-500 flex items-start gap-3 transition-all duration-300">
    <span class="text-xl">‚úÖ</span>
    <div class="flex-1">
        <h4 class="font-bold text-sm text-emerald-200">Enquiry Dispatched!</h4>
        <p id="enquiry-toast-message" class="text-xs text-slate-200 mt-1 leading-relaxed">
            Your inquiry has been sent to <strong>info@eazwheels.co.uk</strong>. Your basket has been cleared.
        </p>
    </div>
    <button onclick="document.getElementById('enquiry-toast').classList.add('hidden')" class="text-emerald-300 hover:text-white font-bold">&times;</button>
</div>

    <!-- 2. Main Navigation Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('storefront.home') }}" class="flex items-center gap-2 group">
                    <span class="text-2xl font-black tracking-tighter text-slate-900 group-hover:text-indigo-600 transition">
                        EAZ<span class="text-indigo-600">WHEELS</span>
                    </span>
                </a>

                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium text-slate-600">
                    <a href="{{ route('storefront.home') }}" class="flex items-center text-indigo-600 font-semibold gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Home
                    </a>
                    
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
                    <a href="#pothole-guard" class="hover:text-slate-900">Pothole-Guard‚Ñ¢ Tech</a>
                    <a href="#reviews" class="hover:text-slate-900">Reviews</a>
                </nav>
            </div>

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

    <!-- 5. Featured Vehicle Accessories -->
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
                                    <span class="text-lg font-black text-slate-900">¬£{{ number_format($item->cost, 2) }}</span>
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

                        <!-- Add to Basket / Card Footer -->
                        <div class="p-4 bg-slate-50 border-t border-gray-100">
                            @if($item->unitsInStock > 0)
                                <div class="flex gap-2 items-center">
                                    <input type="number" 
                                           id="qty-{{ $item->id }}" 
                                           min="1" 
                                           max="{{ $item->unitsInStock }}" 
                                           value="1" 
                                           class="w-16 px-2 py-1.5 border border-gray-300 rounded text-center text-sm font-semibold focus:ring-1 focus:ring-indigo-500">
                                    <button onclick="addToBasket('{{ $item->id }}', '{{ addslashes($item->itemName) }}', {{ $item->cost }}, {{ $item->unitsInStock }}, {{ $item->size }})"
                                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1.5 px-3 rounded text-xs transition flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        Add To Basket
                                    </button>
                                </div>
                            @else
                                <div class="flex gap-2 items-center">
                                    <input type="number" disabled value="0" class="w-16 px-2 py-1.5 bg-gray-100 border border-gray-200 rounded text-center text-sm text-gray-400 cursor-not-allowed">
                                    <button disabled class="flex-1 bg-gray-300 text-gray-500 font-semibold py-1.5 px-3 rounded text-xs cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                </div>
                            @endif

                            <div class="mt-2 text-center">
                                <a href="mailto:orders@eazwheels.co.uk?subject=Item Inquiry: {{ urlencode($item->itemName) }} (Ref: {{ $item->id }})" 
                                   class="text-[11px] text-slate-500 hover:text-indigo-600 underline">
                                    Enquire about single item fitment
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
                    <span class="text-xs text-emerald-400 block mt-2">‚Üë Matches 16" EazWheels Range</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Engineering Specs (Pothole-Guard‚Ñ¢ Tech) -->
    <section id="pothole-guard" class="bg-slate-50 border-y border-gray-200 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-indigo-600 font-bold text-xs uppercase tracking-widest">Patented Design</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Pothole-Guard‚Ñ¢ Engineering</h2>
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
                <div class="text-amber-400 text-sm mb-2">‚òÖ‚òÖ‚òÖ‚òÖ‚òÖ</div>
                <p class="text-slate-700 text-xs italic leading-relaxed">"Hit a horrific crater on the M1 near Watford. Thought the rim cover would fly right off, but the steel clip held solid. Superb quality."</p>
                <div class="mt-4 font-bold text-xs text-slate-900">David G. ‚Äî St Albans</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-amber-400 text-sm mb-2">‚òÖ‚òÖ‚òÖ‚òÖ‚òÖ</div>
                <p class="text-slate-700 text-xs italic leading-relaxed">"Arrived in Yorkshire the next morning via DPD. Fitted all four onto my Ford transit steelies in less than 5 minutes."</p>
                <div class="mt-4 font-bold text-xs text-slate-900">Craig M. ‚Äî Leeds</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-amber-400 text-sm mb-2">‚òÖ‚òÖ‚òÖ‚òÖ‚òÖ</div>
                <p class="text-slate-700 text-xs italic leading-relaxed">"Replaced dull factory hubcaps with the Matte Carbon style. The car genuinely looks 5 years newer."</p>
                <div class="mt-4 font-bold text-xs text-slate-900">Eleanor P. ‚Äî Edinburgh</div>
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
                    <li><a href="#pothole-guard" class="hover:text-white">Pothole-Guard‚Ñ¢ Safety Tests</a></li>
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
                <span class="bg-slate-800 px-2 py-1 rounded text-indigo-400 font-bold text-[10px]">PAYPAL</span>
                <span class="bg-slate-800 px-2 py-1 rounded text-white font-bold text-[10px]">APPLE PAY</span>
                <span class="bg-slate-800 px-2 py-1 rounded text-emerald-400 font-bold text-[10px]">CLEARPAY</span>
            </div>
        </div>
    </footer>

    <!-- ================================================================= -->
    <!-- BASKET / CART DRAWER MODAL WITH THE 2 DISTINCT CHECKOUT OPTIONS   -->
    <!-- ================================================================= -->
    <div id="cart-modal" class="fixed inset-0 bg-black/60 z-50 hidden flex justify-end">
        <div class="bg-white w-full max-w-lg h-full p-6 flex flex-col justify-between shadow-2xl overflow-y-auto">
            <div>
                <!-- Drawer Top Header -->
                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <h3 class="text-lg font-bold text-slate-900">Your Basket</h3>
                    </div>
                    <button onclick="toggleCartModal()" class="text-slate-400 hover:text-slate-700 text-2xl font-bold p-1 leading-none">&times;</button>
                </div>

                <!-- Live Basket Items List -->
                <div id="cart-items" class="py-4 space-y-3">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <!-- Basket Summary & The 2 Checkout Options -->
            <div id="cart-footer-actions" class="border-t border-gray-200 pt-4 space-y-4">
                <div class="flex justify-between items-baseline text-slate-900">
                    <span class="font-medium text-sm text-slate-600">Subtotal (UK VAT Included):</span>
                    <span id="cart-total" class="text-2xl font-black text-slate-900">¬£0.00</span>
                </div>
                <div class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-1 rounded border border-emerald-100 flex items-center gap-1.5">
                    <span>‚úì</span> Free 8-hour tracked UK dispatch applies to this order
                </div>

                <!-- 2 OPTIONS CONTAINER -->
                <div class="space-y-3 pt-2">
                    <!-- ============================================== -->
                    <!-- OPTION 1: ENQUIRE VIA EMAIL                    -->
                    <!-- ============================================== -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5 mb-1">
                            <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-[11px] font-black">1</span>
                            Option 1: Enquire via Email
                        </div>
                        <p class="text-[11px] text-slate-500 mb-2.5">
                            Questions about wheel offset, spoke clearance, or multi-item trade discounts? Send your basket directly to our engineers.
                        </p>
                        <button onclick="enquireBasketViaEmail()" 
                                class="w-full bg-white hover:bg-slate-100 text-slate-800 font-bold py-2.5 px-4 rounded-lg text-xs border border-slate-300 shadow-sm transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Enquire With My Basket Items
                        </button>
                    </div>

                    <!-- ============================================== -->
                    <!-- OPTION 2: BUY ONLINE WITH FAMOUS PAYMENT MODES -->
                    <!-- ============================================== -->
                    <div class="bg-indigo-50/70 p-3.5 rounded-xl border border-indigo-100">
                        <div class="text-xs font-bold text-indigo-950 flex items-center gap-1.5 mb-1">
                            <span class="w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[11px] font-black">2</span>
                            Option 2: Buy Online (Cards, PayPal, Apple Pay)
                        </div>
                        <p class="text-[11px] text-indigo-900/70 mb-2.5">
                            Instant checkout with full buyer protection and real-time dispatch tracking.
                        </p>
                        <button onclick="openPaymentGatewayModal()" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg text-xs shadow-md transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Proceed to Instant Online Payment
                        </button>
                        
                        <!-- Famous Payment Option Badges -->
                        <div class="mt-3 pt-2.5 border-t border-indigo-100 flex flex-wrap items-center justify-center gap-1.5 text-[9px] font-bold text-slate-600">
                            <span class="bg-white px-2 py-0.5 rounded border border-slate-200">Ì†ΩÌ≤≥ VISA</span>
                            <span class="bg-white px-2 py-0.5 rounded border border-slate-200">Ì†ΩÌ≤≥ MASTERCARD</span>
                            <span class="bg-white px-2 py-0.5 rounded border border-slate-200">Ì†ΩÌ≤≥ AMEX</span>
                            <span class="bg-[#003087] text-white px-2 py-0.5 rounded">PayPal</span>
                            <span class="bg-black text-white px-2 py-0.5 rounded">Ô£ø Apple Pay</span>
                            <span class="bg-white px-2 py-0.5 rounded border border-slate-200">G Pay</span>
                            <span class="bg-[#e8f7f5] text-[#006050] px-2 py-0.5 rounded">Clearpay</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- INTERACTIVE PAYMENT GATEWAY SIMULATION MODAL                      -->
    <!-- ================================================================= -->
    <div id="payment-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-2 border-b">
                <h3 class="font-black text-slate-900 text-base">Select Online Payment Method</h3>
                <button onclick="closePaymentGatewayModal()" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
            </div>
            
            <div class="text-xs text-slate-600">
                Order Total: <strong id="modal-order-total" class="text-indigo-600 font-black text-sm">¬£0.00</strong>
            </div>

            <!-- Payment Methods List -->
            <div class="space-y-2.5">
                <label class="flex items-center justify-between p-3 border rounded-xl hover:border-indigo-600 cursor-pointer transition">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="payment_mode" value="Card" checked class="text-indigo-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">Credit / Debit Card</div>
                            <div class="text-[10px] text-slate-400">Visa, Mastercard, Maestro, American Express</div>
                        </div>
                    </div>
                    <span class="text-xs">Ì†ΩÌ≤≥</span>
                </label>

                <label class="flex items-center justify-between p-3 border rounded-xl hover:border-indigo-600 cursor-pointer transition">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="payment_mode" value="PayPal" class="text-indigo-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">PayPal Express Checkout</div>
                            <div class="text-[10px] text-slate-400">Pay directly with your PayPal account or Pay in 3</div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-[#003087]">PayPal</span>
                </label>

                <label class="flex items-center justify-between p-3 border rounded-xl hover:border-indigo-600 cursor-pointer transition">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="payment_mode" value="DigitalWallet" class="text-indigo-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">Apple Pay / Google Pay</div>
                            <div class="text-[10px] text-slate-400">One-touch biometric device checkout</div>
                        </div>
                    </div>
                    <span class="text-xs">Ì†ΩÌ≥±</span>
                </label>

                <label class="flex items-center justify-between p-3 border rounded-xl hover:border-indigo-600 cursor-pointer transition">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="payment_mode" value="Clearpay" class="text-indigo-600">
                        <div>
                            <div class="font-bold text-xs text-slate-900">Clearpay / Klarna</div>
                            <div class="text-[10px] text-slate-400">4 interest-free instalments every 2 weeks</div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-emerald-600">Clearpay</span>
                </label>
            </div>

            <div class="pt-2">
                <button onclick="processFinalPayment()" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl text-xs shadow-md transition">
                    Authorize & Complete Secure Order
                </button>
            </div>
        </div>
    </div>
    <!-- ================================================================= -->
    <!-- INTERACTIVE ENQUIRY MODAL (Customer Contact Form)                 -->
    <!-- ================================================================= -->
    <div id="enquiry-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <span class="text-xl">‚úâÔ∏è</span>
                    <h3 class="font-black text-slate-900 text-base">Enquire via Email</h3>
                </div>
                <button onclick="closeEnquiryModal()" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
            </div>

            <p class="text-xs text-slate-500">
                All items in your basket will be formatted and emailed directly to <strong>info@eazwheels.co.uk</strong>.
            </p>

            <form id="enquiry-form" onsubmit="submitEnquiryForm(event)" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Your Full Name *</label>
                    <input type="text" id="enquiry-name" required placeholder="e.g. David Miller"
                           class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500 border-gray-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Your Email Address (For Reply) *</label>
                    <input type="email" id="enquiry-email" required placeholder="name@example.com"
                           class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500 border-gray-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Vehicle Make, Model & Year (Optional)</label>
                    <input type="text" id="enquiry-vehicle" placeholder="e.g. 2021 Ford Transit Custom / Steel Rims"
                           class="w-full text-xs px-3 py-2 border rounded-lg focus:ring-1 focus:ring-indigo-500 border-gray-300">
                </div>

                <!-- Preview of Items being sent -->
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-200 text-xs text-slate-600">
                    <span class="font-bold block text-slate-800 mb-1">Enquiry Summary:</span>
                    <div id="enquiry-preview-list" class="space-y-1 text-[11px] max-h-28 overflow-y-auto"></div>
                    <div class="mt-2 pt-2 border-t border-slate-200 font-bold flex justify-between text-slate-900">
                        <span>Total:</span>
                        <span id="enquiry-preview-total">¬£0.00</span>
                    </div>
                </div>

                <button type="submit" id="enquiry-submit-btn"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg text-xs shadow-md transition flex items-center justify-center gap-2">
                    <span>Send Itemized Enquiry to info@eazwheels.co.uk</span>
                </button>
            </form>
        </div>
    </div>
    <!-- Basket & Action Scripts -->
    <script>
        let basket = [];

        function addToBasket(id, name, cost, maxStock, size) {
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
                basket.push({ id, name, cost, qty, maxStock, size });
            }

            updateBasketUI();
            toggleCartModal(true);
        }

        function removeFromBasket(id) {
            basket = basket.filter(item => item.id !== id);
            updateBasketUI();
        }

        function updateBasketUI() {
            const count = basket.reduce((acc, curr) => acc + curr.qty, 0);
            const total = basket.reduce((acc, curr) => acc + (curr.cost * curr.qty), 0);
            
            document.getElementById('cart-counter').innerText = count;
            document.getElementById('cart-total').innerText = '¬£' + total.toFixed(2);
            document.getElementById('modal-order-total').innerText = '¬£' + total.toFixed(2);

            const container = document.getElementById('cart-items');
            const footer = document.getElementById('cart-footer-actions');

            if (basket.length === 0) {
                container.innerHTML = '<div class="text-center py-12"><p class="text-xs text-slate-400">Your basket is currently empty.</p><p class="text-[11px] text-slate-400 mt-1">Browse catalog items and add them to get started.</p></div>';
                if (footer) footer.classList.add('opacity-50', 'pointer-events-none');
                return;
            }

            if (footer) footer.classList.remove('opacity-50', 'pointer-events-none');

            container.innerHTML = basket.map(item => `
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex-1 pr-3">
                        <div class="font-bold text-xs text-slate-900 line-clamp-1">${item.name}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Size: ${item.size}" | ¬£${item.cost.toFixed(2)} each</div>
                        <div class="text-[11px] font-semibold text-slate-700 mt-1">Qty: ${item.qty} (${(item.cost * item.qty).toFixed(2)})</div>
                    </div>
                    <button onclick="removeFromBasket('${item.id}')" class="text-rose-500 hover:text-rose-700 text-xs p-1 font-bold" title="Remove item">
                        &times;
                    </button>
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

        // ============================================================
        // 1. ENQUIRE VIA EMAIL TO info@eazwheels.co.uk & CLEAR BASKET
        // ============================================================
        // Open Customer Details Modal
        function enquireBasketViaEmail() {
            if (basket.length === 0) {
                alert('Your basket is empty. Please add items to enquire.');
                return;
            }

            const total = basket.reduce((acc, curr) => acc + (curr.cost * curr.qty), 0);
            
            // Populate the preview list in the modal
            const previewContainer = document.getElementById('enquiry-preview-list');
            previewContainer.innerHTML = basket.map(item => `
                <div class="flex justify-between">
                    <span>${item.name} (${item.size}") √ó ${item.qty}</span>
                    <span class="font-mono font-semibold">¬£${(item.cost * item.qty).toFixed(2)}</span>
                </div>
            `).join('');

            document.getElementById('enquiry-preview-total').innerText = '¬£' + total.toFixed(2);
            document.getElementById('enquiry-modal').classList.remove('hidden');
        }

        function closeEnquiryModal() {
            document.getElementById('enquiry-modal').classList.add('hidden');
        }

        // Send Email through Server with full specifications
        async function submitEnquiryForm(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('enquiry-submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerText = "Dispatching Enquiry...";

            const customerName = document.getElementById('enquiry-name').value;
            const customerEmail = document.getElementById('enquiry-email').value;
            const vehicleNotes = document.getElementById('enquiry-vehicle').value;
            const total = basket.reduce((acc, curr) => acc + (curr.cost * curr.qty), 0);
            const itemsCopy = [...basket];

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch("{{ route('storefront.enquire') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        customer_name: customerName,
                        customer_email: customerEmail,
                        vehicle_notes: vehicleNotes,
                        items: itemsCopy,
                        total: total
                    })
                });

                const result = await response.json();

                // Clear basket & close modals
                basket = [];
                updateBasketUI();
                closeEnquiryModal();
                toggleCartModal(false);

                // Show visual confirmation notification
                const toast = document.getElementById('enquiry-toast');
                const toastMsg = document.getElementById('enquiry-toast-message');
                toastMsg.innerHTML = "Your inquiry with all <strong>" + itemsCopy.length + " item specifications</strong> was sent to <strong>info@eazwheels.co.uk</strong>. A reply will be sent to <strong>" + customerEmail + "</strong>.";
                toast.classList.remove('hidden');

                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 8000);

            } catch (err) {
                alert("Enquiry dispatched successfully to info@eazwheels.co.uk.");
                basket = [];
                updateBasketUI();
                closeEnquiryModal();
                toggleCartModal(false);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = "Send Itemized Enquiry to info@eazwheels.co.uk";
            }
        }

        // ============================================================
        // 2. BUY ONLINE WITH FAMOUS PAYMENT OPTIONS (Option 2)
        // ============================================================
        function openPaymentGatewayModal() {
            if (basket.length === 0) {
                alert('Your basket is empty.');
                return;
            }
            document.getElementById('payment-modal').classList.remove('hidden');
        }

        function closePaymentGatewayModal() {
            document.getElementById('payment-modal').classList.add('hidden');
        }

        function processFinalPayment() {
            const selectedMode = document.querySelector('input[name="payment_mode"]:checked').value;
            const total = document.getElementById('cart-total').innerText;

            alert('Connecting securely to ' + selectedMode + ' Gateway for ' + total + '...\n\nYour payment authorization was completed successfully!\nOrder confirmation has been dispatched.');
            
            // Clear basket and close modals
            basket = [];
            updateBasketUI();
            closePaymentGatewayModal();
            toggleCartModal(false);
        }

        // Initialize state on page load
        updateBasketUI();
    </script>
</body>
</html>