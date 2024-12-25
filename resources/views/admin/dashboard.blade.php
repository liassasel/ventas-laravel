@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    <div class="py-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-white">Dashboard</h2>
            <div class="flex items-center space-x-4">
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center space-x-2">
                    <select name="period" onchange="this.form.submit()" class="bg-gray-700 text-white rounded-md px-4 py-2">
                        <option value="week" {{ $selectedPeriod == 'week' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="month" {{ $selectedPeriod == 'month' ? 'selected' : '' }}>Este Mes</option>
                        <option value="year" {{ $selectedPeriod == 'year' ? 'selected' : '' }}>Este Año</option>
                        <option value="custom" {{ $selectedPeriod == 'custom' ? 'selected' : '' }}>Personalizado</option>
                    </select>
                    <input type="date" name="start_date" value="{{ $customStartDate }}" class="bg-gray-700 text-white rounded-md px-4 py-2 {{ $selectedPeriod != 'custom' ? 'hidden' : '' }}">
                    <input type="date" name="end_date" value="{{ $customEndDate }}" class="bg-gray-700 text-white rounded-md px-4 py-2 {{ $selectedPeriod != 'custom' ? 'hidden' : '' }}">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Filtrar
                    </button>
                </form>
            </div>
        </div>

        <div class="flex flex-wrap -mx-4 mb-8">
            <div class="w-full md:w-1/6 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Ventas Totales</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalSales ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/6 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Monto Total</h3>
                    <p class="text-3xl font-bold text-white cursor-pointer" onclick="toggleCurrency(this)" data-soles="{{ number_format($totalAmountSoles ?? 0, 2) }}" data-dollars="{{ number_format($totalAmountDollars ?? 0, 2) }}">
                        S/. {{ number_format($totalAmountSoles ?? 0, 2) }}
                    </p>
                </div>
            </div>
            <div class="w-full md:w-1/6 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Productos</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalProducts ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/6 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Proveedores</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalSuppliers ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/6 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Usuarios</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/6 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Servicios Técnicos</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalServices ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4 text-white">Productos Más Vendidos</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Cantidad Vendida
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Monto Total (Soles)
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Monto Total (Dólares)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    {{ $product['name'] }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    {{ $product['total_quantity'] }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    S/. {{ number_format($product['total_amount_soles'], 2) }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    $ {{ number_format($product['total_amount_dollars'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white text-center">
                                    No hay datos disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4 text-white">Productos Más Vendidos por Tienda</h3>
            <div class="space-y-4">
                @forelse($topProductsByStore as $storeName => $products)
                    <div class="bg-gray-700 rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2 text-white">{{ $storeName }}</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-600 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Producto
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-600 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Cantidad Vendida
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-600 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Monto Total (Soles)
                                        </th>
                                        <th class="px-5 py-3 border-b-2 border-gray-600 bg-gray-800 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Monto Total (Dólares)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-600 bg-gray-700 text-sm text-white">
                                                {{ $product['name'] }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-600 bg-gray-700 text-sm text-white">
                                                {{ $product['total_quantity'] }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-600 bg-gray-700 text-sm text-white">
                                                S/. {{ number_format($product['total_amount_soles'], 2) }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-600 bg-gray-700 text-sm text-white">
                                                $ {{ number_format($product['total_amount_dollars'], 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-5 py-5 border-b border-gray-600 bg-gray-700 text-sm text-white text-center">
                                                No hay datos disponibles para esta tienda
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-700 rounded-lg p-4">
                        <p class="text-white text-center">No hay datos disponibles para ninguna tienda</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4 text-white">Estadísticas por Usuario</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Usuario
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Total Ventas
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Monto Total (Soles)
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Monto Total (Dólares)
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userStatistics as $stat)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    {{ $stat['name'] }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    {{ $stat['total_sales'] }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    S/. {{ number_format($stat['total_amount_soles'], 2) }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    $ {{ number_format($stat['total_amount_dollars'], 2) }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    <a href="{{ route('user.stats', ['user' => $stat['id']]) }}" 
                                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                        Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white text-center">
                                    No hay datos disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para estadísticas de usuario -->
<div id="userStatsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-white" id="modalTitle">Estadísticas de Usuario</h3>
            <button onclick="closeUserStatsModal()" class="text-white hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-4">
            <select id="periodSelect" onchange="updateUserStats()" class="bg-gray-700 text-white rounded-md px-4 py-2 w-full">
                <option value="week">Esta Semana</option>
                <option value="month" selected>Este Mes</option>
                <option value="year">Este Año</option>
            </select>
        </div>

        <div id="userStatsContent" class="text-white">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-sm font-medium mb-2">Total Ventas</h4>
                    <p id="totalSales" class="text-2xl font-bold">0</p>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-sm font-medium mb-2">Total (Soles)</h4>
                    <p id="totalSoles" class="text-2xl font-bold">S/. 0.00</p>
                </div>
                <div class="bg-gray-700 p-4 rounded-lg">
                    <h4 class="text-sm font-medium mb-2">Total (Dólares)</h4>
                    <p id="totalDollars" class="text-2xl font-bold">$ 0.00</p>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-lg font-medium mb-4">Productos Vendidos</h4>
                <div class="bg-gray-700 rounded-lg p-4">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-semibold uppercase">Producto</th>
                                <th class="text-left text-xs font-semibold uppercase">Cantidad</th>
                                <th class="text-left text-xs font-semibold uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody id="productsTable"></tbody>
                    </table>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-medium mb-4">Ventas Diarias</h4>
                <canvas id="dailySalesChart" class="bg-gray-700 p-4 rounded-lg"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let currentUserId = null;
let dailySalesChart = null;

function openUserStatsModal(userId, userName) {
    currentUserId = userId;
    document.getElementById('modalTitle').textContent = `Estadísticas de ${userName}`;
    document.getElementById('userStatsModal').classList.remove('hidden');
    updateUserStats();
}

function closeUserStatsModal() {
    document.getElementById('userStatsModal').classList.add('hidden');
    if (dailySalesChart) {
        dailySalesChart.destroy();
    }
}

function updateUserStats() {
    if (!currentUserId) return;

    const period = document.getElementById('periodSelect').value;
    fetch(`/dashboard/user-stats/${currentUserId}?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Actualizar estadísticas generales
            document.getElementById('totalSales').textContent = data.total_sales;
            document.getElementById('totalSoles').textContent = `S/. ${data.total_amount_soles.toFixed(2)}`;
            document.getElementById('totalDollars').textContent = `$ ${data.total_amount_dollars.toFixed(2)}`;

            // Actualizar tabla de productos
            const productsTable = document.getElementById('productsTable');
            productsTable.innerHTML = '';
            Object.entries(data.products_sold).forEach(([product, stats]) => {
                productsTable.innerHTML += `
                    <tr>
                        <td class="py-2">${product}</td>
                        <td class="py-2">${stats.quantity}</td>
                        <td class="py-2">S/. ${stats.total.toFixed(2)}</td>
                    </tr>
                `;
            });

            // Actualizar gráfico de ventas diarias
            updateDailySalesChart(data.daily_sales);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar las estadísticas');
        });
}

function updateDailySalesChart(dailySales) {
    const ctx = document.getElementById('dailySalesChart').getContext('2d');
    
    if (dailySalesChart) {
        dailySalesChart.destroy();
    }

    const dates = Object.keys(dailySales);
    const sales = Object.values(dailySales).map(day => day.count);
    const amounts = Object.values(dailySales).map(day => day.total);

    dailySalesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'Número de Ventas',
                    data: sales,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    yAxisID: 'y'
                },
                {
                    label: 'Monto Total (S/.)',
                    data: amounts,
                    borderColor: 'rgb(153, 102, 255)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Número de Ventas',
                        color: 'white'
                    },
                    ticks: {
                        color: 'white'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Monto Total (S/.)',
                        color: 'white'
                    },
                    ticks: {
                        color: 'white'
                    }
                },
                x: {
                    ticks: {
                        color: 'white'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            }
        }
    });
}

// Cerrar modal con Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeUserStatsModal();
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('userStatsModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeUserStatsModal();
    }
});

// Mostrar/ocultar campos de fecha personalizada en el filtro principal
document.querySelector('select[name="period"]').addEventListener('change', function() {
    const customDateInputs = document.querySelectorAll('input[type="date"]');
    if (this.value === 'custom') {
        customDateInputs.forEach(input => input.classList.remove('hidden'));
    } else {
        customDateInputs.forEach(input => input.classList.add('hidden'));
    }
});

function toggleCurrency(element) {
    const soles = element.getAttribute('data-soles');
    const dollars = element.getAttribute('data-dollars');
    if (element.textContent.includes('S/.')) {
        element.textContent = '$ ' + dollars;
    } else {
        element.textContent = 'S/. ' + soles;
    }
}
</script>
@endpush

