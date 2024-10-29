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
            </div>
        </div>
    </div>
</nav>

<!-- Balance Display with Neon Effect -->
<div id="neon-balance-root"></div>

<!-- Action Buttons -->
<div class="flex justify-center gap-4 mt-6">
    <a href="/dashboard/pengelola" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Catatan
    </a>
    <a href="/dashboard/riwayat" class="text-yellow-200 bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
        Riwayat Catatan
    </a>
</div>

<!-- Chart Container -->
<div id="chartContainer" class="mt-6" style="height: 370px; width: 100%;"></div>

<!-- Required Scripts -->
<script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>

<!-- Neon Balance Component -->
<script>
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