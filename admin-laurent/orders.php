<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk | Laurent Hardware</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], },
                    colors: {
                        brand: { 50: '#f0fdf4', 100: '#dcfce7', 500: '#22c55e', 600: '#16a34a', 900: '#14532d' },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen overflow-hidden flex" x-data="ordersPage()">

    <!-- Toast Notification -->
    <div class="fixed top-5 right-5 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none px-4 sm:px-0">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" class="pointer-events-auto flex items-center p-4 rounded-2xl shadow-lg border bg-white" :class="toast.type === 'success' ? 'border-green-100' : 'border-red-100'">
                <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-xl" :class="toast.type === 'success' ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500'">
                    <svg x-show="toast.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <svg x-show="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-bold text-slate-800" x-text="toast.title"></h4>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="toast.message"></p>
                </div>
                <button @click="removeToast(toast.id)" class="ml-auto bg-white text-slate-400 hover:text-slate-900 rounded-lg p-1.5 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    <!-- INCLUDE KOMPONEN -->
    <?php include 'components/sidebar.php'; ?>

    <!-- CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <?php include 'components/topbar.php'; ?>

        <!-- MAIN SCROLLABLE AREA -->
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-8" x-cloak x-show="isAuthenticated">
            
            <div class="space-y-6">
                <!-- Header Title & Filter -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 bg-white p-5 md:p-6 rounded-3xl shadow-sm border border-slate-100">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Pesanan</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau pesanan masuk dan verifikasi pembayaran secara real-time.</p>
                    </div>
                    
                    <div class="w-full sm:w-56">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Filter Status</label>
                        <div class="relative">
                            <select x-model="filterStatus" class="appearance-none w-full bg-slate-50 border border-slate-200 text-slate-700 py-2.5 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white text-sm font-medium transition cursor-pointer">
                                <option value="all">Semua Pesanan</option>
                                <option value="pending">🟡 Menunggu Verifikasi</option>
                                <option value="paid">🟢 Pembayaran Lunas</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ORDERS TABLE -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-xs text-slate-400 uppercase tracking-widest">
                                    <th class="px-6 py-5 font-bold whitespace-nowrap">ID Pesanan & Waktu</th>
                                    <th class="px-6 py-5 font-bold whitespace-nowrap">Informasi Pelanggan</th>
                                    <th class="px-6 py-5 font-bold whitespace-nowrap">Nominal Tagihan</th>
                                    <th class="px-6 py-5 font-bold whitespace-nowrap text-center">Metode Bayar</th>
                                    <th class="px-6 py-5 font-bold whitespace-nowrap text-center">Status</th>
                                    <th class="px-6 py-5 font-bold text-right whitespace-nowrap pr-8">Tindakan Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                <template x-for="order in filteredOrders" :key="order.id">
                                    <tr class="hover:bg-slate-50/80 transition duration-150 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-inner"
                                                     :class="order.status === 'paid' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600'">
                                                    <svg x-show="order.status === 'paid'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    <svg x-show="order.status === 'pending'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                                <div>
                                                    <span class="font-bold text-slate-900 group-hover:text-brand-600 transition-colors" x-text="order.poNumber"></span>
                                                    <div class="text-[11px] font-medium text-slate-400 mt-0.5" x-text="order.date"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-semibold text-slate-800" x-text="order.customer"></div>
                                            <div class="text-[11px] font-medium text-slate-500 bg-slate-100 inline-block px-2 py-0.5 rounded-md mt-1" x-text="order.items + ' Barang Dibeli'"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-black text-slate-900 text-base" x-text="formatRupiah(order.total)"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200" x-text="order.method"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span x-show="order.status === 'pending'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-bold bg-yellow-50 text-yellow-700 border border-yellow-200 shadow-sm">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                                </span>
                                                MENUNGGU
                                            </span>
                                            <span x-show="order.status === 'paid'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-bold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> LUNAS
                                            </span>
                                        </td>
                                        <!-- Action Buttons -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Tombol Terima -->
                                            <button x-show="order.status === 'pending'" @click="openConfirmModal(order, 'paid')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-500 hover:text-white transition tooltip" title="Tandai Lunas">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
</button>
                                            <!-- Tombol Tolak -->
                                            <button x-show="order.status === 'pending'" @click="openConfirmModal(order, 'canceled')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition tooltip" title="Batalkan Pesanan">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
</button>
                                            
                                            <!-- Tombol Lihat Detail -->
                                            <button @click="viewDetails(order)" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white transition font-semibold text-xs gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Detail
                                            </button>
                                        </div>
                                    </td>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="filteredOrders.length === 0">
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-700">Tidak ada pesanan</h3>
                                            <p class="text-sm text-slate-500 mt-1">Belum ada data pesanan yang sesuai dengan filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- MODAL DETAIL PESANAN -->
            <div x-cloak x-show="isDetailModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
                <!-- Klik latar belakang untuk menutup modal -->
                <div @click.away="isDetailModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]"
                     x-show="isDetailModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <!-- Header Modal -->
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Rincian Barang</h3>
                            <p class="text-xs font-semibold text-brand-600" x-text="selectedOrder?.invoice_number"></p>
                        </div>
                        <button @click="isDetailModalOpen = false" class="text-slate-400 hover:text-red-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <!-- Isi Modal -->
                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                        <div x-show="isLoadingItems" class="text-center text-slate-500 py-8">
                            <div class="animate-spin inline-block w-6 h-6 border-2 border-brand-500 border-t-transparent rounded-full mb-2"></div>
                            <p class="text-sm">Memuat rincian...</p>
                        </div>
                        
                        <ul x-show="!isLoadingItems" class="space-y-4">
                            <ul x-show="!isLoadingItems" class="space-y-4">
                            <template x-for="(item, index) in orderItems" :key="index">
                                <li class="flex justify-between items-center border-b border-slate-100 pb-4 mb-2 last:border-0 last:mb-0 last:pb-0">
                                    
                                    <!-- Bagian Kiri: Gambar & Nama -->
                                    <div class="flex items-center gap-4">
                                        <!-- Thumbnail Gambar -->
                                        <div class="w-14 h-14 md:w-16 md:h-16 shrink-0 bg-slate-100 rounded-xl border border-slate-200 overflow-hidden flex items-center justify-center p-1">
                                            <!-- Menggunakan fallback icon jika gambar tidak ditemukan -->
                                            <img :src="item.image" :alt="item.name" class="w-full h-full object-contain" 
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text=No+Image';">
                                        </div>
                                        
                                        <!-- Detail Teks -->
                                        <div>
                                            <h4 class="font-bold text-sm text-slate-800 line-clamp-1" x-text="item.name"></h4>
                                            <p class="text-xs font-medium text-slate-500 mt-1">
                                                <span x-text="item.qty"></span> x <span x-text="formatRupiah(item.price)"></span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Bagian Kanan: Subtotal -->
                                    <div class="font-bold text-brand-600 text-sm bg-brand-50 px-2.5 py-1.5 rounded-lg shrink-0 ml-2" 
                                         x-text="formatRupiah(item.qty * item.price)">
                                    </div>
                                    
                                </li>
                            </template>
                        </ul>
                        </ul>
                    </div>
                    
                    <!-- Footer Modal -->
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex flex-col gap-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-slate-500">Nama Pelanggan:</span>
                            <span class="font-bold text-slate-800" x-text="selectedOrder?.customer_name"></span>
                        </div>
                        
                        <!-- Tambahan: Nomor Telepon / WA -->
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-slate-500">No. Telepon / WA:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800" x-text="selectedOrder?.phone"></span>
                                <!-- Tombol direct to WhatsApp -->
                                <a :href="'https://wa.me/' + (selectedOrder?.phone ? selectedOrder.phone.replace(/^0/, '62').replace(/\D/g, '') : '')" 
                                   target="_blank" 
                                   class="text-green-500 hover:text-green-600 transition" 
                                   title="Hubungi via WhatsApp">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.161.453-.892.853-1.288.932-.303.06-.723.137-2.228-.485-1.802-.74-2.947-2.58-3.037-2.703-.09-.124-.725-.966-.725-1.843s.457-1.31.621-1.484c.135-.143.344-.194.524-.194.062 0 .118-.005.168-.005.158 0 .324-.015.485.378.165.405.539 1.32.586 1.415.048.095.079.206.015.334-.061.127-.095.206-.188.312-.093.107-.197.234-.278.326-.089.102-.184.214-.078.397.105.182.473.782 1.019 1.268.703.626 1.293.82 1.479.914.184.095.293.079.403-.047.112-.127.481-.559.613-.751.13-.191.261-.161.428-.1.168.06 1.059.5 1.242.592.184.094.306.142.352.223.045.08.045.467-.116.92zM12.015 2c5.514 0 10 4.486 10 10s-4.486 10-10 10c-1.856 0-3.593-.509-5.068-1.385l-4.947 1.303 1.325-4.819c-.96-1.503-1.51-3.296-1.51-5.204 0-5.514 4.486-10 10-10z"/></svg>
                                </a>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-sm mt-1">
                            <span class="font-semibold text-slate-500">Alamat:</span>
                            <span class="font-bold text-slate-800 text-right max-w-[200px] sm:max-w-[300px] truncate" x-text="selectedOrder?.address" :title="selectedOrder?.address"></span>
                        </div>
                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-slate-200">
                            <span class="font-bold text-slate-600">Total Tagihan:</span>
                            <span class="font-black text-xl text-slate-900" x-text="formatRupiah(selectedOrder?.grand_total)"></span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL KONFIRMASI STATUS DINAMIS -->
<div x-cloak x-show="confirmAction.open" class="fixed inset-0 z-[80] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div x-show="confirmAction.open" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="confirmAction.open = false"></div>
        
        <div x-show="confirmAction.open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
            
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Ikon Berubah Warna Otomatis -->
                    <div class="mx-auto flex h-14 w-14 shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-12 sm:w-12 transition-colors" :class="confirmAction.iconBg">
                        <svg class="h-6 w-6" :class="confirmAction.iconColor" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="confirmAction.iconPath" />
                        </svg>
                    </div>
                    
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-lg font-bold leading-6 text-slate-900" x-text="confirmAction.title"></h3>
                        <div class="mt-2">
                            <p class="text-sm text-slate-500 leading-relaxed" x-html="confirmAction.message"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                <!-- Tombol Eksekusi Berubah Warna Otomatis -->
                <button type="button" @click="executeUpdateStatus()" class="inline-flex w-full justify-center rounded-xl px-5 py-2.5 text-sm font-bold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors focus:outline-none" :class="confirmAction.btnColor" x-text="confirmAction.btnText">
                </button>
                <button type="button" @click="confirmAction.open = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors focus:outline-none">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ordersPage', () => ({
                isAuthenticated: false,
                isSidebarOpen: false,
                globalSearch: '',
                currentDate: '',
                currentTime: '',
                toasts: [],
                toastIdCounter: 0,
                orders: [],
                filterStatus: 'all',
                // State untuk Modal Detail
                isDetailModalOpen: false,
                selectedOrder: null,
                orderItems: [],
                isLoadingItems: false,
                pendingOrdersCount: 0,

                confirmAction: {
                    open: false, orderId: null, newStatus: '',
                    title: '', message: '', btnText: '', 
                    btnColor: '', iconBg: '', iconColor: '', iconPath: ''
                },

                init() {
                    this.checkAuth();
                    // 1. Panggil penghitung saat halaman pertama kali dimuat
                    this.fetchPendingCount();
                    this.startClock();
                    this.fetchOrders();
                    // 2. Cek database otomatis setiap 10 detik (10000 milidetik)
                    setInterval(() => {
                        this.fetchPendingCount();
                    }, 10000);
                },

                fetchPendingCount() {
                    fetch('api/get_pending_count.php')
                        .then(res => res.json())
                        .then(res => {
                            if(res.status === 'success') {
                                // DETEKSI PESANAN BARU MASUK
                                // Jika angka baru lebih besar dari angka sebelumnya, berarti ada order baru
                                if (res.count > this.pendingOrdersCount && this.pendingOrdersCount !== 0) {
                                    // Panggil Toast Notification jika Anda sedang di halaman dashboard
                                    if (typeof this.showToast === 'function') {
                                        this.showToast('Pesanan Baru!', 'Ada pesanan baru yang menunggu verifikasi Anda.', 'success');
                                    }
                                }
                                
                                // Perbarui angka di badge merah sidebar
                                this.pendingOrdersCount = res.count;
                            }
                        })
                        .catch(err => console.error("Gagal mengecek notifikasi:", err));
                },

                fetchOrders() {
                    this.isLoading = true;
                    fetch('api/get_orders.php') // <--- Ubah di sini (otomatis GET)
                        .then(res => res.json())
                        .then(res => {
                            if(res.status === 'success') {
                                this.orders = res.data;
                            }
                        })
                        .finally(() => { this.isLoading = false; });
                },

                // Fungsi Melihat Detail Pesanan
                viewDetails(order) {
                    this.selectedOrder = order;
                    this.isDetailModalOpen = true;
                    this.isLoadingItems = true;
                    this.orderItems = []; // Kosongkan data sebelumnya

                    // Panggil API dengan parameter action=get_items
                    fetch(`api/get_orders.php?action=get_items&order_id=${order.id}`)
                        .then(res => res.json())
                        .then(res => {
                            if(res.status === 'success') {
                                this.orderItems = res.data;
                            } else {
                                alert("Gagal mengambil data barang.");
                            }
                        })
                        .finally(() => { this.isLoadingItems = false; });
                },

                

                checkAuth() {
                    if (sessionStorage.getItem('admin_auth') !== 'true') {
                        window.location.href = 'login.html';
                    } else {
                        this.isAuthenticated = true;
                    }
                },

                doLogout() {
                    sessionStorage.removeItem('admin_auth');
                    window.location.href = 'login.html';
                },

                startClock() {
                    const updateTime = () => {
                        const now = new Date();
                        this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                        this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
                    };
                    updateTime();
                    setInterval(updateTime, 1000);
                },

                get filteredOrders() {
                    let result = this.orders;
                    if (this.filterStatus !== 'all') {
                        result = result.filter(o => o.status === this.filterStatus);
                    }
                    if (this.globalSearch.trim() !== '') {
                        const search = this.globalSearch.toLowerCase();
                        result = result.filter(o => o.poNumber.toLowerCase().includes(search) || o.customer.toLowerCase().includes(search));
                    }
                    return result.sort((a, b) => b.id - a.id);
                },

                get pendingOrdersCount() {
                    return this.orders.filter(o => o.status === 'pending').length;
                },

                formatRupiah(number) {
                    if (!number) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
                },

                showToast(title, message, type = 'success') {
                    const id = this.toastIdCounter++;
                    this.toasts.push({ id, title, message, type, visible: true });
                    setTimeout(() => { this.removeToast(id); }, 4000);
                },

                removeToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 300);
                    }
                },

                // Membuka Modal & Menyesuaikan Tampilan Berdasarkan Aksi
                openConfirmModal(order, newStatus) {
                    this.confirmAction.orderId = order.id;
                    this.confirmAction.newStatus = newStatus;
                    
                    if (newStatus === 'paid') {
                        this.confirmAction.title = 'Terima & Verifikasi';
                        this.confirmAction.message = `Anda akan memverifikasi pesanan <b>${order.poNumber}</b> senilai <b>${this.formatRupiah(order.total)}</b>.<br><br><span class="text-[11px] text-red-500 font-bold">*Pastikan dana benar-benar sudah masuk/mutasi dicek.</span>`;
                        this.confirmAction.btnText = 'Ya, Tandai Lunas';
                        this.confirmAction.btnColor = 'bg-green-600 hover:bg-green-700';
                        this.confirmAction.iconBg = 'bg-green-100';
                        this.confirmAction.iconColor = 'text-green-600';
                        this.confirmAction.iconPath = 'M5 13l4 4L19 7'; // Ikon Centang
                    } else {
                        this.confirmAction.title = 'Batalkan Pesanan';
                        this.confirmAction.message = `Anda akan membatalkan pesanan <b>${order.poNumber}</b> atas nama <b>${order.customer}</b>. Tindakan ini tidak dapat dikembalikan.`;
                        this.confirmAction.btnText = 'Ya, Batalkan';
                        this.confirmAction.btnColor = 'bg-red-600 hover:bg-red-700';
                        this.confirmAction.iconBg = 'bg-red-100';
                        this.confirmAction.iconColor = 'text-red-600';
                        this.confirmAction.iconPath = 'M6 18L18 6M6 6l12 12'; // Ikon Silang
                    }
                    
                    this.confirmAction.open = true;
                },

                // Mengeksekusi API Setelah Tombol "Ya" Ditekan
                executeUpdateStatus() {
                    const orderId = this.confirmAction.orderId;
                    const newStatus = this.confirmAction.newStatus;
                    
                    fetch('api/get_orders.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: orderId, status: newStatus })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if(res.status === 'success') {
                            this.showToast('Berhasil Diperbarui!', res.message, 'success');
                            this.fetchOrders();
                            this.fetchPendingCount(); // Segarkan badge sidebar otomatis
                        } else {
                            this.showToast('Gagal Memperbarui', res.message, 'error');
                        }
                    })
                    .catch(() => {
                        this.showToast('Kesalahan Jaringan', 'Gagal terhubung ke server.', 'error');
                    })
                    .finally(() => {
                        this.confirmAction.open = false; // Otomatis tutup modal
                    });
                },

                
            }));
        });
    </script>
</body>
</html>