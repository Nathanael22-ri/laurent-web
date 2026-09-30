<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Laurent Hardware</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], },
                    colors: {
                        brand: { 50: '#f0fdf4', 100: '#dcfce7', 500: '#22c55e', 600: '#16a34a', 900: '#14532d' },
                        accent: { 500: '#6366f1' }
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
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen overflow-hidden flex" x-data="adminDashboard()">

    <!-- Toast Notification Tetap di Sini (Atau bisa Anda pisah ke components/toast.php juga) -->
    <div class="fixed top-5 right-5 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none px-4 sm:px-0">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" class="pointer-events-auto flex items-center p-4 rounded-2xl shadow-lg border bg-white border-green-100">
                <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-xl bg-green-100 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
        
        <!-- INCLUDE KOMPONEN TOPBAR -->
        <?php include 'components/topbar.php'; ?>

        <!-- MAIN SCROLLABLE AREA (HANYA DASHBOARD) -->
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-8" x-cloak x-show="isAuthenticated">
            
            <div class="space-y-6 sm:space-y-8">
                <!-- Welcome Section -->
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" style="color: #344f96;">Laurent Hardware</h1>
                    <p class="text-sm text-slate-500 mt-1">Ringkasan performa penjualan Laurent Hardware hari ini.</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Stat 1 -->
                    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:scale-[1.02] transition-transform">
                        <div class="absolute top-0 right-0 p-4 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform">
                            <svg class="w-24 h-24 text-accent-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.97-1.3-3.15-3.61-3.15-2.39 0-3.83 1.44-3.83 3.21 0 2.41 2.11 3.2 4.15 3.67 1.84.45 2.27 1.05 2.27 1.84 0 1-.95 1.54-2.28 1.54-1.63 0-2.33-.82-2.43-1.89H8.22c.11 2.2 1.62 3.32 3.82 3.32 2.5 0 3.99-1.37 3.99-3.23.01-2.07-1.4-2.9-4.14-3.57z"/></svg>
                        </div>
                        <div class="relative z-10">
                            <p class="text-sm font-semibold text-slate-500 mb-1">Total Pendapatan (Bulan Ini)</p>
<h4 class="text-2xl sm:text-3xl font-black text-slate-800" x-text="formatRupiah(stats.revenue)"></h4>                       
 </div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:scale-[1.02] transition-transform">
                        <div class="absolute top-0 right-0 p-4 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform">
                            <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
                        </div>
                        <div class="relative z-10">
                            <p class="text-sm font-semibold text-slate-500 mb-1">Pesanan Sukses (Bulan Ini)</p>
<h4 class="text-2xl sm:text-3xl font-black text-slate-800" x-text="stats.success_orders"></h4>                        
</div>
                    </div>

                    <!-- Stat 3 (Actionable) -->
                    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:scale-[1.02] transition-transform cursor-pointer border-l-4 border-l-red-500">
                        <div class="absolute top-0 right-0 p-4 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform">
                            <svg class="w-24 h-24 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        </div>
                        <div class="relative z-10">
                            <p class="text-sm font-semibold text-slate-500 mb-1">Menunggu Verifikasi</p>
<h4 class="text-2xl sm:text-3xl font-black text-red-600" x-text="stats.pending_orders"></h4>                        
</div>
                    </div>
                    
                    <!-- Stat 4 -->
                    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:scale-[1.02] transition-transform">
                        <div class="absolute top-0 right-0 p-4 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform">
                            <svg class="w-24 h-24 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        </div>
                        <div class="relative z-10">
                            <p class="text-sm font-semibold text-slate-500 mb-1">Total Pelanggan Baru</p>
<h4 class="text-2xl sm:text-3xl font-black text-slate-800" x-text="stats.new_customers"></h4>
                        </div>
                    </div>
                </div>

                <!-- Charts & Activity Area -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Grafik Area -->
                    <div class="lg:col-span-2 glass-panel p-6 rounded-3xl">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-bold text-lg text-slate-800">Tren Penjualan (7 Hari Terakhir)</h3>
                            <button class="text-xs font-semibold text-brand-600 bg-brand-50 px-3 py-1.5 rounded-lg hover:bg-brand-100 transition">Unduh Laporan</button>
                        </div>
                        <div class="relative h-64 w-full">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Aktivitas Terbaru -->
                    <div class="flex-1 overflow-y-auto pr-2 space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
    <template x-for="act in recentActivities" :key="act.id">
        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10"
                 :class="act.status === 'pending' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600'">
                <!-- Icon Centang / Jam -->
                <svg x-show="act.status !== 'pending'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="act.status === 'pending'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl shadow-sm bg-white border border-slate-100 ml-4 md:ml-0">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="font-bold text-sm text-slate-800" x-text="act.invoice_number"></h4>
                    <time class="text-[10px] text-slate-400 font-medium" x-text="formatDate(act.created_at)"></time>
                </div>
                <p class="text-xs text-slate-500">
                    <span class="font-bold text-slate-700" x-text="act.customer_name"></span> 
                    <span x-text="act.status === 'pending' ? 'membuat pesanan baru sebesar' : 'melunasi tagihan'"></span> 
                    <span class="font-bold text-slate-700" x-text="formatRupiah(act.grand_total)"></span>.
                </p>
            </div>
        </div>
    </template>
    
    <div x-show="recentActivities.length === 0" class="text-center py-4 text-sm text-slate-500">Belum ada aktivitas.</div>
</div>
                </div>
            </div>
        </main>
    </div>

    <!-- Skrip Alpine.js (Dipertahankan di Sini) -->
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminDashboard', () => ({
            isAuthenticated: false,
            isSidebarOpen: false,
            globalSearch: '',
            currentDate: '',
            currentTime: '',
            toasts: [],
            toastIdCounter: 0,
            
            // State Data Nyata
            stats: {
                revenue: 0,
                success_orders: 0,
                pending_orders: 0,
                new_customers: 0
            },
            recentActivities: [],

            init() {
                this.checkAuth();
                this.startClock();
                if(this.isAuthenticated) {
                    this.fetchDashboardData();
                }
            },

            fetchDashboardData() {
                // Mengambil seluruh data statistik, grafik, dan riwayat sekaligus
                fetch('api/get_dashboard.php')
                    .then(res => res.json())
                    .then(res => {
                        if(res.status === 'success') {
                            this.stats = {
                                revenue: res.data.revenue,
                                success_orders: res.data.success_orders,
                                pending_orders: res.data.pending_orders,
                                new_customers: res.data.new_customers
                            };
                            this.recentActivities = res.data.activities;
                            
                            // Initialize Chart dengan data dari database
                            this.initChart(res.data.chart_labels, res.data.chart_data);
                        }
                    })
                    .catch(err => console.error("Gagal load dashboard:", err));
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

            formatRupiah(number) {
                if (!number) return 'Rp 0';
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
            },

            formatDate(dateString) {
                const options = { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
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

            initChart(labels, dataPoints) {
                const ctx = document.getElementById('salesChart');
                if(!ctx) return;
                if(window.mySalesChart) window.mySalesChart.destroy();

                window.mySalesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels, // Label tanggal dinamis
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: dataPoints, // Data angka dinamis
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#22c55e',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => this.formatRupiah(ctx.raw) } } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: (val) => val >= 1000000 ? (val/1000000)+'Jt' : val } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        }));
    });
</script>
</body>
</html>