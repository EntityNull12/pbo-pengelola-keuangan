<!-- Navigation -->
<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <div class="text-white text-lg font-bold text-3xl">Dashboard</div>
        <div class="relative">
            <!-- Icon garis 3 (hamburger menu) -->
            <button id="menu-button" class="text-yellow-200 hover:bg-yellow-700 hover:text-white px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            
            <!-- Dropdown menu -->
            <div id="menu-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg z-10">
                <a href="/about" class="block px-4 py-2 text-yellow-200 hover:bg-yellow-700 hover:text-white rounded-t-lg">About</a>
                <a href="/profile" class="block px-4 py-2 text-yellow-200 hover:bg-yellow-700 hover:text-white">Profile</a>
                <a href="/logout" class="block px-4 py-2 text-yellow-200 hover:bg-yellow-700 hover:text-white">Logout</a>
            </div>
        </div>
    </div>
</nav>

<!-- Balance Display with Neon Effect -->
<div id="neon-balance-root"></div>

<!-- Action Buttons -->
<div class="flex justify-center gap-4 mt-6 mb-4">
    <a href="/dashboard/pengelola" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Catatan
    </a>
    <a href="/dashboard/riwayat" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Riwayat Catatan
    </a>
</div>

<!-- Spacer -->
<div class="h-4"></div>

<!-- Filter Chart -->
<div class="mb-4 flex gap-4 max-w-2xl mx-auto mt-4">
    <!-- Filter Type -->
    <select id="filterType" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-2.5">
        <option value="harian">Harian</option>
        <option value="bulanan">Bulanan</option>
        <option value="tahunan">Tahunan</option>
    </select>

    <!-- Filter Bulan - Hanya untuk tipe Harian -->
    <div id="filterBulanContainer">
        <select id="filterBulan" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-2.5">
            <?php
            $bulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            foreach ($bulan as $num => $name) {
                $selected = $num == date('n') ? 'selected' : '';
                echo "<option value='$num' $selected>$name</option>";
            }
            ?>
        </select>
    </div>

    <!-- Filter Tahun - Untuk tipe Harian dan Bulanan -->
    <div id="filterTahunContainer">
        <select id="filterTahun" class="bg-gray-900 border border-yellow-200 text-yellow-200 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-2.5">
            <?php
            for ($y = 2000; $y <= 2029; $y++) {
                $selected = $y == date('Y') ? 'selected' : '';
                echo "<option value='$y' $selected>$y</option>";
            }
            ?>
        </select>
    </div>
</div>
<!-- Chart Container -->
<div class="mt-6 max-w-2xl mx-auto bg-gray-800 p-4 rounded-lg">
    <div class="overflow-x-auto" style="height: 400px;">
        <div style="min-width: 800px; height: 370px;">
            <canvas id="lineChart"></canvas>
        </div>
    </div>
    <!-- Zoom Controls -->
    <div class="flex justify-end mt-2 space-x-2">
        <button id="reset-zoom" class="px-3 py-1 bg-gray-700 text-white rounded hover:bg-gray-600 text-sm">
            Reset Zoom
        </button>
    </div>
</div>
<!-- Required Scripts -->
<script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>

<script>
// Data for the chart (replace these with your dynamic data)
// Handler untuk perubahan tipe filter
function handleFilterTypeChange() {
    const filterType = document.getElementById('filterType').value;
    const filterBulanContainer = document.getElementById('filterBulanContainer');
    const filterTahunContainer = document.getElementById('filterTahunContainer');

    switch(filterType) {
        case 'harian':
            filterBulanContainer.classList.remove('hidden');
            filterTahunContainer.classList.remove('hidden');
            break;
        case 'bulanan':
            filterBulanContainer.classList.add('hidden');
            filterTahunContainer.classList.remove('hidden');
            break;
        case 'tahunan':
            filterBulanContainer.classList.add('hidden');
            filterTahunContainer.classList.add('hidden');
            break;
    }

    updateChart();
}

function generateEmptyMonthlyData(year) {
    return Array.from({ length: 12 }, (_, month) => ({
        label: new Date(year, month, 1).toISOString(),
        income: 0,
        expense: 0
    }));
}

async function updateChart() {
    const filterType = document.getElementById('filterType').value;
    const filterBulan = document.getElementById('filterBulan');
    const filterTahun = document.getElementById('filterTahun');
    let params = new URLSearchParams();
    
    params.append('type', filterType);
    
    try {
        document.body.style.cursor = 'wait';
        
        switch(filterType) {
            case 'harian':
                params.append('bulan', filterBulan.value);
                params.append('tahun', filterTahun.value);
                break;
            case 'bulanan':
                params.append('tahun', filterTahun.value);
                break;
        }

        const response = await fetch(`/dashboard/getChartData?${params.toString()}`);
        const data = await response.json();

        if (filterType === 'bulanan') {
            // Create fixed labels for all months
            const fixedLabels = namaBulan.map((_, index) => 
                new Date(filterTahun.value, index, 1).toISOString()
            );
            
            // Create arrays for income and expense data
            const incomeData = new Array(12).fill(0);
            const expenseData = new Array(12).fill(0);
            
            // Fill in the actual data
            data.labels.forEach((label, index) => {
                const month = new Date(label).getMonth();
                incomeData[month] = data.incomeData[index] || 0;
                expenseData[month] = data.expenseData[index] || 0;
            });

            lineChart.data.labels = fixedLabels;
            lineChart.data.datasets[0].data = incomeData;
            lineChart.data.datasets[1].data = expenseData;
        } else if (filterType === 'tahunan') {
            // Untuk tampilan tahunan, gunakan tahun sebagai label
            lineChart.data.labels = data.labels;
            lineChart.data.datasets[0].data = data.incomeData;
            lineChart.data.datasets[1].data = data.expenseData;
        } else {
            // For daily view, use data as is
            lineChart.data.labels = data.labels;
            lineChart.data.datasets[0].data = data.incomeData;
            lineChart.data.datasets[1].data = data.expenseData;
        }

        transactions = data.transactions;
        
        // Recalculate min and max values
        const { min: newYMin, max: newYMax } = getMinMaxValues(
            lineChart.data.datasets[0].data,
            lineChart.data.datasets[1].data
        );
        lineChart.options.scales.y.min = newYMin;
        lineChart.options.scales.y.max = newYMax;
        
        lineChart.update('none');
    } catch (error) {
        console.error('Error updating chart:', error);
    } finally {
        document.body.style.cursor = 'default';
    }
}

// Event listeners untuk filter
document.getElementById('filterType').addEventListener('change', handleFilterTypeChange);
document.getElementById('filterBulan').addEventListener('change', updateChart);
document.getElementById('filterTahun').addEventListener('change', updateChart);

// Inisialisasi tampilan awal
handleFilterTypeChange();

// Format ke mata uang Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(angka);
}
// Format tanggal ke Indonesia
function formatTanggal(tanggal) {
    return new Date(tanggal).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

// Data untuk chart dari PHP
// Data untuk chart dari PHP
let chartData = <?= json_encode($chartData) ?>;
let labels = chartData.labels;
let incomeData = chartData.incomeData;
let expenseData = chartData.expenseData;
let transactions = chartData.transactions;

const ctx = document.getElementById('lineChart').getContext('2d');

// Fungsi untuk mendapatkan min dan max values
function getMinMaxValues(data1, data2) {
    const allData = [...data1, ...data2].filter(val => val !== null && val !== undefined);
    const min = Math.min(...allData);
    const max = Math.max(...allData);
    const range = max - min;
    
    return {
        min: Math.max(0, min - (range * 0.1)),
        max: max + (range * 0.1)
    };
}

const { min: yMin, max: yMax } = getMinMaxValues(incomeData, expenseData);
const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

// Update chart configuration
let lineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: incomeData,
                borderColor: 'rgba(34, 197, 94, 1)',
                backgroundColor: 'rgba(34, 197, 94, 0.2)',
                fill: true,
                pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                tension: 0,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: 'rgba(34, 197, 94, 1)',
                pointHoverBorderWidth: 3,
            },
            {
                label: 'Pengeluaran',
                data: expenseData,
                borderColor: 'rgba(239, 68, 68, 1)',
                backgroundColor: 'rgba(239, 68, 68, 0.2)',
                fill: true,
                pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                tension: 0,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: 'rgba(239, 68, 68, 1)',
                pointHoverBorderWidth: 3,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 12,
                        weight: 'bold'
                    },
                    color: '#FEFCE8'
                }
            },
            tooltip: {
                enabled: true,
                mode: 'index',
                intersect: false,
                position: 'nearest',
                backgroundColor: 'rgba(17, 24, 39, 0.8)',
                titleColor: '#FEFCE8',
                bodyColor: '#FEFCE8',
                borderColor: '#FEFCE8',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    title: function(context) {
                        const filterType = document.getElementById('filterType').value;
                        const date = context[0].label;
                        
                        switch(filterType) {
                            case 'bulanan':
                                return `${namaBulan[new Date(date).getMonth()]} ${new Date(date).getFullYear()}`;
                            case 'tahunan':
                                return date; // Gunakan label tahun langsung
                            default:
                                return formatTanggal(date);
                        }
                    },
                    label: function(context) {
                        const nilai = context.raw;
                        const tipe = context.dataset.label;
                        if (nilai === null || nilai === undefined) return null;
                        return `${tipe}: ${formatRupiah(nilai)}`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                min: yMin,
                max: yMax,
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)',
                    drawBorder: true
                },
                ticks: {
                    callback: function(value) {
                        return formatRupiah(value);
                    },
                    color: '#FEFCE8',
                    count: 10
                }
            },
            x: {
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)',
                    drawBorder: true
                },
                ticks: {
                    maxRotation: 45,
                    minRotation: 45,
                    color: '#FEFCE8',
                    autoSkip: false,
                    callback: function(value, index) {
                        const filterType = document.getElementById('filterType').value;
                        
                        switch(filterType) {
                            case 'harian':
                                return new Date(this.getLabelForValue(value)).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'short'
                                });
                            case 'bulanan':
                                return namaBulan[new Date(this.getLabelForValue(value)).getMonth()];
                            case 'tahunan':
                                // Gunakan label langsung untuk tahun
                                return this.getLabelForValue(value);
                            default:
                                return new Date(this.getLabelForValue(value)).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'short'
                                });
                        }
                    }
                }
            }
        }
    }
});
// Event listener untuk reset zoom
document.getElementById('reset-zoom').addEventListener('click', () => {
    lineChart.resetZoom();
});

// Neon Balance Component
const NeonBalanceDisplay = ({ saldo = 0 }) => {
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID').format(amount);
    };

    return React.createElement(
        'div',
        { className: 'flex flex-col items-center gap-4 py-8' },
        [
            React.createElement(
                'h6',
                { 
                    className: 'font-medium text-lg text-yellow-200',
                    key: 'title'
                },
                'Uang saat ini'
            ),
            React.createElement(
                'div',
                { 
                    className: 'relative',
                    key: 'balance-container'
                },
                React.createElement(
                    'div',
                    {
                        className: 'font-bold text-5xl text-yellow-200 animate-pulse relative'
                    },
                    [
                        React.createElement(
                            'span',
                            {
                                className: 'relative z-10',
                                key: 'main-text'
                            },
                            `Rp ${formatCurrency(saldo)}`
                        ),
                        React.createElement(
                            'span',
                            {
                                className: 'absolute inset-0 text-yellow-200 blur-sm',
                                key: 'glow-1'
                            },
                            `Rp ${formatCurrency(saldo)}`
                        ),
                        React.createElement(
                            'span',
                            {
                                className: 'absolute inset-0 text-yellow-200 blur-md',
                                key: 'glow-2'
                            },
                            `Rp ${formatCurrency(saldo)}`
                        ),
                        React.createElement(
                            'span',
                            {
                                className: 'absolute inset-0 text-yellow-200 blur-lg opacity-50',
                                key: 'glow-3'
                            },
                            `Rp ${formatCurrency(saldo)}`
                        )
                    ]
                )
            )
        ]
    );
};

// Initialize the React component with the saldo from PHP
const saldo = <?= $saldo ?? 0 ?>;
const balanceRoot = document.getElementById('neon-balance-root');
const root = ReactDOM.createRoot(balanceRoot);
root.render(React.createElement(NeonBalanceDisplay, { saldo: saldo }));

// Dropdown menu functionality
const menuButton = document.getElementById('menu-button');
const menuDropdown = document.getElementById('menu-dropdown');

menuButton.addEventListener('click', () => {
    menuDropdown.classList.toggle('hidden');
});

window.addEventListener('click', (e) => {
    if (!menuButton.contains(e.target) && !menuDropdown.contains(e.target)) {
        menuDropdown.classList.add('hidden');
    }
});
</script>
