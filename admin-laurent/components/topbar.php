<!-- Header Atas (Mobile & Search) -->
<header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 z-30 shrink-0">
    <div class="flex items-center gap-4">
        <!-- Hamburger Menu -->
        <button @click="isSidebarOpen = true" class="lg:hidden p-2 -ml-2 rounded-xl text-slate-500 hover:bg-slate-100 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        
        <!-- Search Bar Global -->
        <div class="hidden sm:flex relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" x-model="globalSearch" placeholder="Cari No. Order / Pelanggan..." class="w-72 pl-10 pr-4 py-2.5 bg-slate-100 border-transparent rounded-xl text-sm focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-200 transition-all outline-none text-slate-700">
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        <!-- Date/Clock -->
        <div class="hidden md:block text-right">
            <p class="text-xs font-semibold text-slate-500" x-text="currentDate"></p>
            <p class="text-sm font-bold text-slate-800" x-text="currentTime"></p>
        </div>
        
        <div class="w-px h-8 bg-slate-200 hidden md:block"></div>

        <!-- Notifikasi Bell -->
        <button class="relative p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span x-show="pendingOrdersCount > 0" class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
        </button>
    </div>
</header>