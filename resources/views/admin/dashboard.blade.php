@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in-up">
    <!-- Header Area -->
    <div class="flex justify-between items-center relative z-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight drop-shadow-sm">Overview Dashboard</h1>
            <p class="text-pink-600 font-medium mt-1">Pantau performa dan stok toko Anda hari ini</p>
        </div>
    </div>

    <!-- 4 Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
        
        <!-- Card 1 -->
        <div class="bg-gradient-to-br from-pink-50 via-white to-pink-100/60 backdrop-blur-xl rounded-3xl shadow-[0_8px_25px_rgba(244,114,182,0.12)] border border-pink-200/90 p-6 flex items-center gap-5 transform hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgba(236,72,153,0.22)] transition-all duration-300 group">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 flex justify-center items-center text-white shadow-md shadow-pink-500/30 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-xs text-pink-700 font-extrabold tracking-wider uppercase">Total Bahan</p>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ $totalBahan }} <span class="text-xs font-bold text-pink-500 bg-pink-100/80 px-2 py-0.5 rounded-md ml-1">Jenis</span></p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-gradient-to-br from-rose-50 via-white to-rose-100/60 backdrop-blur-xl rounded-3xl shadow-[0_8px_25px_rgba(244,63,94,0.12)] border border-rose-200/90 p-6 flex items-center gap-5 transform hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgba(225,29,72,0.22)] transition-all duration-300 group">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 flex justify-center items-center text-white shadow-md shadow-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-rose-700 font-extrabold tracking-wider uppercase">Stok Menipis</p>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ $stokMenipis->count() }} <span class="text-xs font-bold text-rose-600 bg-rose-100/80 px-2 py-0.5 rounded-md ml-1">Jenis</span></p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-gradient-to-br from-fuchsia-50 via-white to-pink-100/60 backdrop-blur-xl rounded-3xl shadow-[0_8px_25px_rgba(217,70,239,0.12)] border border-pink-200/90 p-6 flex items-center gap-5 transform hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgba(217,70,239,0.22)] transition-all duration-300 group">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 flex justify-center items-center text-white shadow-md shadow-fuchsia-500/30 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <p class="text-xs text-fuchsia-700 font-extrabold tracking-wider uppercase">Pesanan Hari Ini</p>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ $pesananHariIni }} <span class="text-xs font-bold text-fuchsia-600 bg-fuchsia-100/80 px-2 py-0.5 rounded-md ml-1">Pesanan</span></p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-gradient-to-br from-purple-50 via-white to-pink-100/60 backdrop-blur-xl rounded-3xl shadow-[0_8px_25px_rgba(168,85,247,0.12)] border border-pink-200/90 p-6 flex items-center gap-5 transform hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgba(168,85,247,0.22)] transition-all duration-300 group">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-700 to-pink-950 flex justify-center items-center text-white shadow-md shadow-pink-900/30 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-pink-900 font-extrabold tracking-wider uppercase">Total Reseller</p>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ $totalReseller }} <span class="text-xs font-bold text-pink-700 bg-pink-100/80 px-2 py-0.5 rounded-md ml-1">Outlet</span></p>
            </div>
        </div>

    </div>

    <!-- Main Content Row -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 relative z-10">
        
        <!-- Line Chart (Grafik Penggunaan) -->
        <div class="xl:col-span-2 bg-gradient-to-br from-white via-pink-50/40 to-rose-50/50 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgba(244,114,182,0.1)] border border-pink-200/80 flex flex-col p-6">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-pink-100/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-700 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-800">Grafik Ketersediaan Stok Bahan Baku</h3>
                        <p class="text-sm text-pink-600/80 font-medium">Memantau sisa stok dari seluruh bahan baku yang ada</p>
                    </div>
                </div>
            </div>
            <div class="flex-1 w-full relative min-h-[350px]">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <!-- Table Stok Terendah -->
        <div class="bg-gradient-to-br from-white via-pink-50/40 to-rose-50/50 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgba(244,114,182,0.1)] border border-pink-200/80 flex flex-col overflow-hidden">
            <div class="p-6 border-b border-pink-200/60 bg-gradient-to-r from-pink-100/70 via-rose-50/40 to-transparent flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-800">Stok Terendah</h3>
                    <p class="text-sm text-rose-600/80 font-medium">Bahan baku yang butuh restock (&lt; 50)</p>
                </div>
            </div>
            <div class="flex-1 overflow-auto p-2">
                <table class="w-full text-sm text-left border-separate border-spacing-y-2 px-4">
                    <thead>
                        <tr class="text-pink-900/60 text-xs uppercase tracking-wider font-extrabold">
                            <th class="px-3 py-2">Nama Bahan Baku</th>
                            <th class="px-3 py-2 text-right">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokMenipis->take(5) as $bahan)
                        <tr class="group transition-all duration-200 hover:bg-rose-100/40 rounded-xl">
                            <td class="px-3 py-3">
                                <div class="font-bold text-gray-800">{{ $bahan->nama_bahan }}</div>
                            </td>
                            <td class="px-3 py-3 text-right">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-600 shadow-xs border border-rose-200">
                                    {{ $bahan->stok }} {{ $bahan->satuan }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-4 py-12 text-center text-gray-400">
                                <div class="w-16 h-16 mx-auto mb-3 bg-green-50 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="font-bold text-green-700">Semua stok aman!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 pt-3">
                <a href="{{ route('bahan-baku.index') }}" class="flex items-center justify-center w-full bg-gradient-to-r from-pink-600 via-pink-700 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white font-bold py-3.5 rounded-xl shadow-[0_4px_15px_rgba(219,39,119,0.35)] hover:shadow-[0_6px_20px_rgba(219,39,119,0.45)] transition-all duration-300 transform hover:-translate-y-0.5">
                    Lihat Semua Inventaris
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<script>
    Chart.defaults.font.family = "'Outfit', sans-serif";
    Chart.defaults.color = '#9d174d';

    const ctx = document.getElementById('lineChart').getContext('2d');
    
    // Gradient definitions for chart bars (Vibrant Pink to Rose theme)
    let gradientPink = ctx.createLinearGradient(0, 0, 0, 350);
    gradientPink.addColorStop(0, '#db2777'); // vibrant pink-600
    gradientPink.addColorStop(1, '#f472b6'); // soft pink-400

    let gradientHover = ctx.createLinearGradient(0, 0, 0, 350);
    gradientHover.addColorStop(0, '#be185d'); // deep pink-700
    gradientHover.addColorStop(1, '#ec4899'); // pink-500

    const labels = [];
    const stockData = [];

    @foreach($chartBahan as $bahan)
        labels.push('{{ $bahan->nama_bahan }}');
        stockData.push({{ $bahan->stok }});
    @endforeach

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sisa Stok Bahan',
                data: stockData,
                backgroundColor: gradientPink,
                hoverBackgroundColor: gradientHover,
                borderColor: '#be185d',
                borderWidth: 1.5,
                borderRadius: 10,
                barThickness: 34,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#831843',
                    titleColor: '#fdf2f8',
                    bodyColor: '#ffffff',
                    borderColor: '#f472b6',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6,
                    cornerRadius: 12,
                    titleFont: { size: 13, weight: 'bold', family: "'Outfit', sans-serif" },
                    bodyFont: { size: 12, weight: 'bold', family: "'Outfit', sans-serif" }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { 
                        color: '#9d174d',
                        font: { weight: '600' } 
                    },
                    grid: { 
                        color: 'rgba(244, 114, 182, 0.2)', 
                        drawBorder: false, 
                        borderDash: [5, 5] 
                    },
                    border: { display: false }
                },
                x: { 
                    grid: { display: false, drawBorder: false },
                    ticks: { 
                        color: '#4b5563',
                        font: { weight: '600' } 
                    },
                    border: { display: false }
                }
            }
        }
    });
</script>
@endsection
