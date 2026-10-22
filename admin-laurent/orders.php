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

    <!-- INCLUDE KOMPONEN SIDEBAR -->
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
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau pesanan masuk dan verifikasi status pemrosesan secara real-time.</p>
                    </div>
                    
                    <div class="w-full sm:w-64">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Filter Status</label>
                        <div class="relative">
                            <select x-model="filterStatus" class="appearance-none w-full bg-slate-50 border border-slate-200 text-slate-700 py-2.5 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white text-sm font-medium transition cursor-pointer">
                                <option value="all">Semua Pesanan</option>
                                <option value="pending">🟡 Menunggu Verifikasi</option>
                                <option value="paid">🟢 Pembayaran Lunas</option>
                                <option value="processing">📦 Dikemas & Siap Kirim</option>
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
                                                     :class="{
                                                         'bg-yellow-50 text-yellow-600': order.status === 'pending',
                                                         'bg-green-50 text-green-600': order.status === 'paid',
                                                         'bg-blue-50 text-blue-600': order.status === 'processing'
                                                     }">
                                                    <svg x-show="order.status === 'pending'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <svg x-show="order.status === 'paid'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    <svg x-show="order.status === 'processing'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
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
                                        
                                        <!-- STATUS BADGES -->
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
                                            <span x-show="order.status === 'processing'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> DIKEMAS
                                            </span>
                                        </td>

                                        <!-- ACTION BUTTONS -->
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <button x-show="order.status === 'pending'" @click="openConfirmModal(order, 'paid')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-500 hover:text-white transition" title="Tandai Lunas">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                                <button x-show="order.status === 'pending'" @click="openConfirmModal(order, 'canceled')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition" title="Batalkan Pesanan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                                <button x-show="order.status === 'paid'" @click="openConfirmModal(order, 'processing')" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition font-semibold text-xs gap-1.5 border border-blue-100" title="Proses & Kemas Pesanan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                                    Kemas Barang
                                                </button>
                                                <button @click="viewDetails(order)" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition font-semibold text-xs gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Detail
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- MODAL DETAIL PESANAN -->
            <div x-cloak x-show="isDetailModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
                <div @click.away="isDetailModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]"
                     x-show="isDetailModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Rincian Barang</h3>
                            <p class="text-xs font-semibold text-brand-600" x-text="selectedOrder?.poNumber || selectedOrder?.invoice_number"></p>
                        </div>
                        <button @click="isDetailModalOpen = false" class="text-slate-400 hover:text-red-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                        <div x-show="isLoadingItems" class="text-center text-slate-500 py-8">
                            <div class="animate-spin inline-block w-6 h-6 border-2 border-brand-500 border-t-transparent rounded-full mb-2"></div>
                            <p class="text-sm">Memuat rincian...</p>
                        </div>
                        
                        <ul x-show="!isLoadingItems" class="space-y-4">
                            <template x-for="(item, index) in orderItems" :key="index">
                                <li class="flex justify-between items-center border-b border-slate-100 pb-4 mb-2 last:border-0 last:mb-0 last:pb-0">
                                    <div class="flex items-center gap-4">
                                        <!-- THUMBNAIL BISA DIKLIK -->
                                        <div @click="openImagePreview(item.image)" class="w-14 h-14 md:w-16 md:h-16 shrink-0 bg-slate-100 rounded-xl border border-slate-200 overflow-hidden flex items-center justify-center p-1 cursor-pointer hover:opacity-80 transition relative group" title="Klik untuk memperbesar">
                                            <img :src="item.image" :alt="item.name" class="w-full h-full object-contain" 
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text=No+Image';">
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-sm text-slate-800 line-clamp-1" x-text="item.name"></h4>
                                            <p class="text-xs font-medium text-slate-500 mt-1">
                                                <span x-text="item.qty"></span> x <span x-text="formatRupiah(item.price)"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="font-bold text-brand-600 text-sm bg-brand-50 px-2.5 py-1.5 rounded-lg shrink-0 ml-2" 
                                         x-text="formatRupiah(item.qty * item.price)">
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex flex-col gap-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-slate-500">Nama Pelanggan:</span>
                            <span class="font-bold text-slate-800" x-text="selectedOrder?.customer || selectedOrder?.customer_name"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-semibold text-slate-500">No. Telepon / WA:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800" x-text="selectedOrder?.phone"></span>
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
                            <span class="font-black text-xl text-slate-900" x-text="formatRupiah(selectedOrder?.total || selectedOrder?.grand_total)"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL LIGHTBOX / PREVIEW GAMBAR BESAR -->
            <div x-cloak x-show="isImageModalOpen" class="fixed inset-0 z-[90] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md">
                <div @click.away="isImageModalOpen = false" class="relative bg-white rounded-3xl shadow-2xl max-w-xl w-full p-6 overflow-hidden flex flex-col items-center"
                     x-show="isImageModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="w-full flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-base">Pratinjau Gambar Produk</h3>
                        <button @click="isImageModalOpen = false" class="text-slate-400 hover:text-red-500 transition p-1 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="w-full max-h-[70vh] flex items-center justify-center bg-slate-50 rounded-2xl p-3 border border-slate-100 overflow-hidden">
                        <img :src="previewImageUrl" class="max-h-[60vh] w-auto object-contain rounded-xl shadow-sm" alt="Pratinjau Produk">
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
                isDetailModalOpen: false,
                selectedOrder: null,
                orderItems: [],
                isLoadingItems: false,
                pendingOrdersCount: 0,

                // State Admin Dinamis
                adminName: 'Super Admin',
                adminEmail: 'admin@laurent.com',
                get adminAvatar() {
                    return `https://ui-avatars.com/api/?name=${encodeURIComponent(this.adminName)}&background=22c55e&color=fff&bold=true`;
                },

                // State Modal Preview Gambar
                isImageModalOpen: false,
                previewImageUrl: '',

                openImagePreview(imageUrl) {
                    this.previewImageUrl = imageUrl;
                    this.isImageModalOpen = true;
                },

                confirmAction: {
                    open: false, orderId: null, newStatus: '',
                    title: '', message: '', btnText: '', 
                    btnColor: '', iconBg: '', iconColor: '', iconPath: ''
                },

                init() {
                    this.checkAuth();
                    this.fetchPendingCount();
                    this.startClock();
                    this.fetchOrders();
                    setInterval(() => {
                        this.fetchPendingCount();
                    }, 10000);
                },

                fetchPendingCount() {
                    fetch('api/get_pending_count.php')
                        .then(res => res.json())
                        .then(res => {
                            if(res.status === 'success') {
                                if (res.count > this.pendingOrdersCount && this.pendingOrdersCount !== 0) {
                                    if (typeof this.showToast === 'function') {
                                        this.showToast('Pesanan Baru!', 'Ada pesanan baru yang menunggu verifikasi Anda.', 'success');
                                    }
                                }
                                this.pendingOrdersCount = res.count;
                            }
                        })
                        .catch(err => console.error("Gagal mengecek notifikasi:", err));
                },

                fetchOrders() {
                    this.isLoading = true;
                    fetch('api/get_orders.php')
                        .then(res => res.json())
                        .then(res => {
                            if(res.status === 'success') {
                                this.orders = res.data;
                            }
                        })
                        .finally(() => { this.isLoading = false; });
                },

                viewDetails(order) {
                    this.selectedOrder = order;
                    this.isDetailModalOpen = true;
                    this.isLoadingItems = true;
                    this.orderItems = [];

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
                        if (sessionStorage.getItem('admin_name')) {
                            this.adminName = sessionStorage.getItem('admin_name');
                        }
                        if (sessionStorage.getItem('admin_email')) {
                            this.adminEmail = sessionStorage.getItem('admin_email');
                        }
                    }
                },

                doLogout() {
                    sessionStorage.removeItem('admin_auth');
                    sessionStorage.removeItem('admin_name');
                    sessionStorage.removeItem('admin_email');
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
                        result = result.filter(o => (o.poNumber && o.poNumber.toLowerCase().includes(search)) || (o.customer && o.customer.toLowerCase().includes(search)));
                    }
                    return result.sort((a, b) => b.id - a.id);
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
                        this.confirmAction.iconPath = 'M5 13l4 4L19 7';
                    } else if (newStatus === 'processing') {
                        this.confirmAction.title = 'Kemas & Siapkan Pengiriman';
                        this.confirmAction.message = `Ubah status pesanan <b>${order.poNumber}</b> menjadi <b>"Dikemas dan Segera Dikirim"</b>?<br><br><span class="text-[11px] text-blue-600 font-semibold">Status pada halaman lacak pelanggan akan otomatis diperbarui.</span>`;
                        this.confirmAction.btnText = 'Ya, Kemas Barang';
                        this.confirmAction.btnColor = 'bg-blue-600 hover:bg-blue-700';
                        this.confirmAction.iconBg = 'bg-blue-100';
                        this.confirmAction.iconColor = 'text-blue-600';
                        this.confirmAction.iconPath = 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4';
                    } else {
                        this.confirmAction.title = 'Batalkan Pesanan';
                        this.confirmAction.message = `Anda akan membatalkan pesanan <b>${order.poNumber}</b> atas nama <b>${order.customer}</b>. Tindakan ini tidak dapat dikembalikan.`;
                        this.confirmAction.btnText = 'Ya, Batalkan';
                        this.confirmAction.btnColor = 'bg-red-600 hover:bg-red-700';
                        this.confirmAction.iconBg = 'bg-red-100';
                        this.confirmAction.iconColor = 'text-red-600';
                        this.confirmAction.iconPath = 'M6 18L18 6M6 6l12 12';
                    }
                    
                    this.confirmAction.open = true;
                },

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
                            this.fetchPendingCount();
                        } else {
                            this.showToast('Gagal Memperbarui', res.message, 'error');
                        }
                    })
                    .catch(() => {
                        this.showToast('Kesalahan Jaringan', 'Gagal terhubung ke server.', 'error');
                    })
                    .finally(() => {
                        this.confirmAction.open = false;
                    });
                },
            }));
        });
    </script>
</body>
</html>