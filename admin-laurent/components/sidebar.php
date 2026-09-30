<!-- Mobile Overlay -->
<div x-show="isSidebarOpen" x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden" @click="isSidebarOpen = false" x-transition.opacity></div>

<!-- SIDEBAR -->
<aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white transition-transform duration-300 lg:translate-x-0 lg:static lg:w-72 flex flex-col border-r border-slate-800">
    <!-- Sidebar Header (Logo) -->
    <div class="h-20 flex items-center px-6 border-b border-slate-800 shrink-0 relative">
        <div class="flex items-center gap-3">
            <div class="bg-brand-500 p-2 rounded-xl shadow-[0_0_15px_rgba(34,197,94,0.4)]">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-lg leading-tight tracking-tight">Laurent<span class="text-brand-400">Admin</span></h2>
                <p class="text-[10px] text-slate-400 font-medium tracking-widest uppercase">Workspace</p>
            </div>
        </div>
        <button @click="isSidebarOpen = false" class="lg:hidden absolute right-4 p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <div class="flex-1 overflow-y-auto custom-scrollbar py-6 px-4 space-y-1">
        <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>
        
        <a href="admin.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group hover:bg-slate-800 hover:text-white text-slate-300">
    <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
    Dashboard
</a>
        
        <a href="orders.php" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all group hover:bg-slate-800 hover:text-white text-slate-300">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        Pesanan Masuk
    </div>
    <!-- Badge notifikasi tetap dipertahankan -->
    <span x-show="pendingOrdersCount > 0" class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full" x-text="pendingOrdersCount"></span>
</a>

        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all group cursor-not-allowed opacity-50" title="Fitur dalam pengembangan">
            <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Katalog Produk
        </a>
        
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all group cursor-not-allowed opacity-50" title="Fitur dalam pengembangan">
            <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Data Pelanggan
        </a>

        <div class="mt-8 mb-2">
            <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pengaturan</p>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all group cursor-not-allowed opacity-50">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Sistem Profil
            </a>
        </div>
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-slate-800 shrink-0">
        <div class="bg-slate-800 rounded-2xl p-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=Admin+Laurent&background=22c55e&color=fff&bold=true" alt="User" class="w-10 h-10 rounded-full border border-slate-600">
                <div class="hidden lg:block overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">Super Admin</p>
                    <p class="text-xs text-slate-400 truncate">admin@laurent.com</p>
                </div>
            </div>
            <button @click="doLogout()" class="p-2 text-red-400 hover:text-white hover:bg-red-500 rounded-lg transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </div>
    </div>
</aside>