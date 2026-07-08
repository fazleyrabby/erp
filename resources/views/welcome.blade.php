<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RHTECH ERP - Premium Enterprise Resource Planning crafted for SMEs, manufacturing, retail, and wholesale in Bangladesh. Scale operations, automate workflows, and drive business growth.">
    <title>RHTECH ERP - Elevate & Automate Your Business Operations</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- Tailwind v4 Custom Theme Configuration -->
    <style type="text/theme">
        @theme {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --color-bg-dark: #0f172a;
            --color-brand-primary: #8b5cf6;
            --color-brand-accent: #6366f1;
        }
    </style>

    <!-- Custom CSS for Font & Entrance Animations -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="text-slate-300 antialiased selection:bg-violet-600 selection:text-white overflow-x-hidden">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-lg border-b border-white/5">
        <nav class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="#" class="flex items-center gap-2.5">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-xl shadow-lg">R</span>
                <span class="font-bold text-2xl tracking-tight text-white">RHTECH <span class="text-violet-500 font-medium">ERP</span></span>
            </a>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#problems" class="text-sm text-slate-400 hover:text-white transition-colors">Pain Points</a>
                <a href="#screenshots" class="text-sm text-slate-400 hover:text-white transition-colors">Tour</a>
                <a href="#modules" class="text-sm text-slate-400 hover:text-white transition-colors">Modules</a>
                <a href="#industries" class="text-sm text-slate-400 hover:text-white transition-colors">Industries</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="/login" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Login</a>
                <a href="#contact" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-white text-slate-950 font-bold hover:bg-slate-100 transition-all active:scale-98">
                    Book a Demo
                </a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative py-24 lg:py-32 overflow-hidden">
        <!-- Background gradients -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[350px] bg-violet-600/10 rounded-full blur-[140px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-12 gap-16 items-center">
            <!-- Hero Copywriting -->
            <div class="lg:col-span-5 space-y-8 animate-fade-in text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-950/40 border border-violet-850/30 text-violet-400 text-xs font-semibold">
                    Built for Bangladesh's Growing Businesses
                </div>

                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-[1.1]">
                    Stop juggling software.<br>
                    <span class="bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">Automate your business.</span>
                </h1>

                <p class="text-lg text-slate-400 leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Ditch manual work and fragile spreadsheets. Integrate your Inventory, Purchasing, POS, Accounting, and HR into one secure, lightning-fast platform designed to increase margins.
                </p>

                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="#contact" class="px-8 py-4 rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-bold shadow-lg hover:shadow-violet-600/25 hover:-translate-y-0.5 transition-all active:scale-98 text-center">
                        Book a Free Demo
                    </a>
                    <a href="#screenshots" class="px-8 py-4 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 text-white font-semibold transition-all hover:-translate-y-0.5 active:scale-98 text-center">
                        See System Showcase
                    </a>
                </div>
            </div>

            <!-- Hero Right: High-Fidelity Overlapping Screenshot Stack -->
            <div class="lg:col-span-7 animate-fade-in delay-100 relative h-[380px] md:h-[450px] w-full flex items-center justify-center mt-12 lg:mt-0">
                <!-- Stack Item 1: Payroll (Left Bottom) -->
                <div class="absolute left-0 bottom-4 w-[75%] bg-slate-900 border border-white/5 rounded-xl shadow-xl overflow-hidden opacity-60 hover:opacity-100 hover:z-40 hover:scale-102 hover:border-white/20 transition-all duration-300 transform -rotate-2">
                    <div class="bg-slate-800 border-b border-white/5 px-3 py-1.5 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                        <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                        <span class="ml-2 text-[10px] text-slate-400 font-mono">rhtech.xyz/payroll</span>
                    </div>
                    <img src="{{ asset('landing/payroll.png') }}" class="w-full h-auto block object-cover" alt="Payroll Stacking View">
                </div>

                <!-- Stack Item 2: Inventory (Right Middle) -->
                <div class="absolute right-0 top-4 w-[75%] bg-slate-900 border border-white/5 rounded-xl shadow-xl overflow-hidden opacity-60 hover:opacity-100 hover:z-40 hover:scale-102 hover:border-white/20 transition-all duration-300 transform rotate-2">
                    <div class="bg-slate-800 border-b border-white/5 px-3 py-1.5 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                        <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                        <span class="ml-2 text-[10px] text-slate-400 font-mono">rhtech.xyz/inventory</span>
                    </div>
                    <img src="{{ asset('landing/inventory.png') }}" class="w-full h-auto block object-cover" alt="Inventory Stacking View">
                </div>

                <!-- Stack Item 3: Dashboard (Center Top Focus) -->
                <div class="absolute w-[80%] bg-slate-900 border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-30 hover:scale-102 transition-transform duration-300">
                    <div class="bg-slate-800 border-b border-white/5 px-4 py-2.5 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                        <span class="ml-4 text-xs text-slate-400 font-mono">rhtech.xyz/dashboard</span>
                    </div>
                    <img src="{{ asset('landing/dashboard.png') }}" class="w-full h-auto block object-cover" alt="Dashboard Focus View">
                </div>
            </div>
        </div>
    </section>

    <!-- Business Pain Points -->
    <section id="problems" class="py-24 border-t border-white/5 bg-slate-900/40 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center space-y-4 mb-16">
                <span class="text-xs uppercase tracking-widest text-rose-500 font-bold">The Reality</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Is administrative overhead strangling your growth?</h2>
                <p class="text-slate-400">Most businesses in Bangladesh stay small not due to sales, but because they get bogged down in chaotic manual systems.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Pain 1 -->
                <div class="p-8 rounded-xl border border-white/5 bg-slate-900 hover:border-rose-950 hover:shadow-lg transition-all duration-300">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-wider">Pain #01</span>
                    <h3 class="text-lg font-bold text-white mt-2 mb-3">Inventory Leakage</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Stocks go missing, warehouses are uncoordinated, and items run out right when customers are ready to pay.</p>
                </div>
                <!-- Pain 2 -->
                <div class="p-8 rounded-xl border border-white/5 bg-slate-900 hover:border-rose-950 hover:shadow-lg transition-all duration-300">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-wider">Pain #02</span>
                    <h3 class="text-lg font-bold text-white mt-2 mb-3">Excel & Ledger Dependency</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Relying on staff to update complex sheets leads to transcription errors, stolen logs, and slow verification.</p>
                </div>
                <!-- Pain 3 -->
                <div class="p-8 rounded-xl border border-white/5 bg-slate-900 hover:border-rose-950 hover:shadow-lg transition-all duration-300">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-wider">Pain #03</span>
                    <h3 class="text-lg font-bold text-white mt-2 mb-3">Delayed Billing & Cashflow</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Writing bills manually slows down invoicing, creating lag times in cash collections and high outstanding debts.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Showcase Tour (Detailed Screenshots Section) -->
    <section id="screenshots" class="py-24 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <span class="text-xs uppercase tracking-widest text-violet-500 font-bold">System Tour</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Explore the RHTECH ERP Interface</h2>
                <p class="text-slate-400">Review the clean, structured modules designed for high daily performance.</p>
            </div>

            <!-- Showcase cards -->
            <div class="space-y-20">
                
                <!-- Tour Item 1: Inventory -->
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-5 space-y-6">
                        <span class="text-xs font-bold text-violet-400 uppercase tracking-wider">Inventory & Warehouse</span>
                        <h3 class="text-2xl font-bold text-white">Stock Ledger Control</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Complete visibility of items across multi-warehouse setups. Configure products, manage units, generate barcodes, and receive real-time notifications on threshold levels.
                        </p>
                    </div>
                    <div class="lg:col-span-7 bg-slate-900 border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                        <div class="bg-slate-800 border-b border-white/5 px-4 py-2 text-xs text-slate-400 font-mono">rhtech.xyz/inventory</div>
                        <img src="{{ asset('landing/inventory.png') }}" class="w-full h-auto block" alt="Inventory Management Screenshot">
                    </div>
                </div>

                <!-- Tour Item 2: Accounting -->
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-5 lg:order-last space-y-6">
                        <span class="text-xs font-bold text-violet-400 uppercase tracking-wider">Finance & Ledger</span>
                        <h3 class="text-2xl font-bold text-white">Double-Entry Accounting</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Maintain strict chart of accounts, generate general ledgers, automate vouchers, manage financial reports, and review balance sheets instantly.
                        </p>
                    </div>
                    <div class="lg:col-span-7 bg-slate-900 border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                        <div class="bg-slate-800 border-b border-white/5 px-4 py-2 text-xs text-slate-400 font-mono">rhtech.xyz/ledger</div>
                        <img src="{{ asset('landing/ledger.png') }}" class="w-full h-auto block" alt="Double Entry Ledger Screenshot">
                    </div>
                </div>

                <!-- Tour Item 3: Payroll -->
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-5 space-y-6">
                        <span class="text-xs font-bold text-violet-400 uppercase tracking-wider">Payroll & Scheduling</span>
                        <h3 class="text-2xl font-bold text-white">Shift & Attendance Planner</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Track attendance records, allocate shift patterns, establish schedules, and automatically compile salary sheets based on logs.
                        </p>
                    </div>
                    <div class="lg:col-span-7 bg-slate-900 border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                        <div class="bg-slate-800 border-b border-white/5 px-4 py-2 text-xs text-slate-400 font-mono">rhtech.xyz/payroll</div>
                        <img src="{{ asset('landing/payroll.png') }}" class="w-full h-auto block" alt="Payroll Shift Planner Screenshot">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Interactive Modules Section -->
    <section id="modules" class="py-24 border-t border-white/5 bg-slate-900/40">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <span class="text-xs uppercase tracking-widest text-violet-500 font-bold">System Capabilities</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Engineered for real business results</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Inventory -->
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900 hover:border-violet-500/30 transition-all duration-300">
                    <h3 class="text-lg font-bold text-white mb-2">Inventory Control</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Save up to 15% on stock costs by eliminating duplicate ordering and unallocated warehouse wastage.</p>
                </div>
                <!-- Accounting -->
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900 hover:border-violet-500/30 transition-all duration-300">
                    <h3 class="text-lg font-bold text-white mb-2">Automated Ledgers</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Get accurate Balance Sheets instantly. Close your monthly accounts in minutes instead of days.</p>
                </div>
                <!-- POS -->
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900 hover:border-violet-500/30 transition-all duration-300">
                    <h3 class="text-lg font-bold text-white mb-2">Retail Point of Sale</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Super-fast billing interface with barcode support. Keep queues moving and automatically sync transactions with stock.</p>
                </div>
                <!-- CRM -->
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900 hover:border-violet-500/30 transition-all duration-300">
                    <h3 class="text-lg font-bold text-white mb-2">Lead Tracking</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Never lose a potential customer. Log communications, assign accounts, and track your pipeline steps.</p>
                </div>
                <!-- HR & Payroll -->
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900 hover:border-violet-500/30 transition-all duration-300">
                    <h3 class="text-lg font-bold text-white mb-2">Shift & Salary Control</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Manage complex employee shifts, track biometric log entries, and calculate net salaries without discrepancies.</p>
                </div>
                <!-- Reports -->
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900 hover:border-violet-500/30 transition-all duration-300">
                    <h3 class="text-lg font-bold text-white mb-2">Executive Reports</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Make decisions based on real facts. Monitor top-selling items, cash flows, and overall productivity levels instantly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Industries Served -->
    <section id="industries" class="py-24 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <span class="text-xs uppercase tracking-widest text-violet-500 font-bold">Adaptability</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Configured for your specific workflow</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Industry 1 -->
                <div class="p-6 rounded-xl border border-white/5 bg-white/[0.01] hover:bg-white/[0.03] transition-colors">
                    <h4 class="text-white font-bold mb-2">Manufacturing</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Track raw materials, monitor Bills of Materials (BOM), and track multi-stage assembly lines.</p>
                </div>
                <!-- Industry 2 -->
                <div class="p-6 rounded-xl border border-white/5 bg-white/[0.01] hover:bg-white/[0.03] transition-colors">
                    <h4 class="text-white font-bold mb-2">Retail & Wholesale</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Handle large bulk invoicing, client credit parameters, barcodes, and quick checkouts.</p>
                </div>
                <!-- Industry 3 -->
                <div class="p-6 rounded-xl border border-white/5 bg-white/[0.01] hover:bg-white/[0.03] transition-colors">
                    <h4 class="text-white font-bold mb-2">Restaurants</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Raw ingredient depletion logs, kitchen slip processing, and busy dining POS panels.</p>
                </div>
                <!-- Industry 4 -->
                <div class="p-6 rounded-xl border border-white/5 bg-white/[0.01] hover:bg-white/[0.03] transition-colors">
                    <h4 class="text-white font-bold mb-2">Pharmacies</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Track medicinal batches, manage expiry dates, and automate medicine reorders.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us / Outcomes -->
    <section class="py-24 border-t border-white/5 bg-slate-900/40">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5 space-y-6">
                <span class="text-xs uppercase tracking-widest text-violet-500 font-bold">Why RHTECH</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Uncompromising performance for scale</h2>
                <p class="text-slate-400 text-sm leading-relaxed">
                    We don't just sell system tools. We deploy robust, localized infrastructure designed to eliminate errors, lower management fatigue, and support your transition to digital operations.
                </p>
            </div>
            
            <div class="lg:col-span-7 grid grid-cols-2 gap-6">
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900">
                    <span class="text-3xl font-extrabold text-violet-400 font-serif">99.8%</span>
                    <h4 class="text-white font-bold text-sm mt-2 mb-1">Inventory Precision</h4>
                    <p class="text-xs text-slate-400">Eliminate manual counting mistakes and physical stock leaks.</p>
                </div>
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900">
                    <span class="text-3xl font-extrabold text-violet-400 font-serif">4x Faster</span>
                    <h4 class="text-white font-bold text-sm mt-2 mb-1">Invoicing Routines</h4>
                    <p class="text-xs text-slate-400">Instantly generate and record transactions into ledgers.</p>
                </div>
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900">
                    <span class="text-3xl font-extrabold text-violet-400 font-serif">15+ Hrs</span>
                    <h4 class="text-white font-bold text-sm mt-2 mb-1">Weekly Time Saved</h4>
                    <p class="text-xs text-slate-400">Ditch daily manual balance compilations and reporting reviews.</p>
                </div>
                <div class="p-6 rounded-xl border border-white/5 bg-slate-900">
                    <span class="text-3xl font-extrabold text-violet-400 font-serif">Real-Time</span>
                    <h4 class="text-white font-bold text-sm mt-2 mb-1">Dashboard Insight</h4>
                    <p class="text-xs text-slate-400">Check current margins and outstanding balance logs immediately.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust / Testimonials -->
    <section class="py-24 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <span class="text-xs uppercase tracking-widest text-violet-500 font-bold">Credibility</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white">Trusted by growing teams</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="p-8 rounded-xl border border-white/5 bg-slate-900/40 space-y-6">
                    <p class="text-slate-300 italic leading-relaxed text-sm">
                        "Deploying RHTECH ERP resolved our stock leakage issues completely. We now track raw materials across three separate warehouses in Chittagong Metro seamlessly, and our accounts close instantly."
                    </p>
                    <div>
                        <h5 class="text-white font-bold text-sm">Managing Director</h5>
                        <p class="text-xs text-slate-500">Ctg Packaging & Manufacturing Ltd.</p>
                    </div>
                </div>
                <!-- Testimonial 2 -->
                <div class="p-8 rounded-xl border border-white/5 bg-slate-900/40 space-y-6">
                    <p class="text-slate-300 italic leading-relaxed text-sm">
                        "The time schedule and shift configuration features saved us hours of manual HR work. Salary calculation steps are processed automatically, and errors have plummeted to zero."
                    </p>
                    <div>
                        <h5 class="text-white font-bold text-sm">Operations Director</h5>
                        <p class="text-xs text-slate-500">Dhaka Wholesale Distribution</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process -->
    <section class="py-24 border-t border-white/5 bg-slate-900/40">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <span class="text-xs uppercase tracking-widest text-violet-500 font-bold">The Journey</span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white">How we ensure implementation success</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="space-y-4">
                    <span class="text-4xl font-extrabold text-white/10 font-serif">01</span>
                    <h4 class="text-white font-bold">Analysis</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">We audit your current accounting charts and inventory storage setups to tailor templates.</p>
                </div>
                <!-- Step 2 -->
                <div class="space-y-4">
                    <span class="text-4xl font-extrabold text-white/10 font-serif">02</span>
                    <h4 class="text-white font-bold">Configuration</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Our specialists configure specific tax structures, regional parameters, and warehouse locations.</p>
                </div>
                <!-- Step 3 -->
                <div class="space-y-4">
                    <span class="text-4xl font-extrabold text-white/10 font-serif">03</span>
                    <h4 class="text-white font-bold">Staff Training</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">We run targeted interactive sessions for your account team and inventory managers.</p>
                </div>
                <!-- Step 4 -->
                <div class="space-y-4">
                    <span class="text-4xl font-extrabold text-white/10 font-serif">04</span>
                    <h4 class="text-white font-bold">Active Support</h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Enjoy direct priority support lines to handle adjustments and data updates fast.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA / Contact Form -->
    <section id="contact" class="py-24 border-t border-white/5 relative overflow-hidden">
        <!-- Background light blur -->
        <div class="absolute bottom-[-100px] left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-violet-600/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 text-center space-y-12 relative z-10">
            <div class="space-y-4">
                <span class="text-xs uppercase tracking-widest text-violet-500 font-bold">Get Started</span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-white">Request a Personalized Demo</h2>
                <p class="text-slate-400 text-sm max-w-xl mx-auto">
                    Tell us about your operations structure, and our team will prepare a localized demo staging environment tailored to your inventory needs.
                </p>
            </div>

            <!-- Booking Action Button -->
            <div class="pt-4">
                <a href="/login" class="inline-flex items-center justify-center px-10 py-5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-bold text-lg shadow-xl hover:shadow-violet-600/25 hover:-translate-y-0.5 transition-all active:scale-98">
                    Launch Interactive Staging Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-500 py-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">R</span>
                <span class="font-semibold text-lg tracking-tight text-white">RHTECH ERP</span>
            </div>
            
            <p class="text-xs">&copy; 2026 RHTECH Ltd. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
