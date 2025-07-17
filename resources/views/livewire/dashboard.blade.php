<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center justify-between">

        <span><i class="fa-solid fa-chart-line text-primary-600 mr-2"></i>Dashboard baru</span>

    </h1>

    @if ($activeBudget)
    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded-lg border shadow-sm">
            <p class="text-gray-500 text-sm">Transaksi Hari Ini</p>
            <p class="text-2xl font-bold text-primary-600">{{ $totalTransactionsToday }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border shadow-sm">
            <p class="text-gray-500 text-sm">Pengeluaran Hari Ini</p>
            <p class="text-2xl font-bold text-red-600">Rp{{ number_format($totalSpentToday, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border shadow-sm">
            <p class="text-gray-500 text-sm">Sisa Limit Hari Ini</p>
            <p class="text-2xl font-bold text-blue-600">Rp{{ number_format($limitToday, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border shadow-sm">
            <p class="text-gray-500 text-sm">Total Transaksi Bulan Ini</p>
            <p class="text-2xl font-bold text-primary-600">{{ $totalTransactionsThisMonth }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border shadow-sm">
            <p class="text-gray-500 text-sm">Pengeluaran Bulan Ini</p>
            <p class="text-2xl font-bold text-red-600">Rp{{ number_format($totalSpentThisMonth, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border shadow-sm">
            <p class="text-gray-500 text-sm">Sisa Budget</p>
            <p class="text-2xl font-bold text-green-600">Rp{{ number_format($budgetLeft, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="mt-6">
        <p class="text-sm text-gray-500 italic">Target Tabungan:
            <span class="text-amber-600 font-semibold">Rp{{ number_format($targetSavings, 0, ',', '.') }}</span>
        </p>
    </div>
    <div id="chart" class="bg-white p-4 rounded shadow mt-6"></div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // document.addEventListener('livewire:load', function () {
            const chart = new ApexCharts(document.querySelector("#chart"), {
                chart: {
                    type: 'line',
                    height: 300,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Pengeluaran',
                    data: @json(collect($chartData)->pluck('total'))
                }],
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            if (val >= 1000000000) return (val / 1000000000).toFixed(1) + 'M';
                            if (val >= 1000000) return (val / 1000000).toFixed(1) + 'jt';
                            if (val >= 1000) return (val / 1000).toFixed(0) + 'rb';
                            return val;
                        },
                        style: {
                            fontSize: '12px',
                            colors: ['#6b7280'],
                        }
                    }
                },
                xaxis: {
                    categories: @json(collect($chartData)->pluck('date')),
                    labels: { rotate: -45 }
                },
                stroke: {
                    curve: 'smooth'
                },
                colors: ['#3b82f6'],
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return 'Rp' + val.toLocaleString();
                        }
                    }
                }
            });
    
            chart.render();
        // });
    </script>
    @endpush
    @else
    <div class="text-sm text-gray-500 italic">
        <i class="fa-solid fa-circle-info mr-1"></i> Tidak ada budget aktif saat ini.
    </div>
    @endif
</div>