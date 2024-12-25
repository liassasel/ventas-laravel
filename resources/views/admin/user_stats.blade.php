@extends('layouts.app')

@section('title', 'Estadísticas de Usuario')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-white">Estadísticas de {{ $user->name }}</h2>
            <div class="flex items-center space-x-4">
                <form action="{{ route('user.stats', ['user' => $user->id]) }}" method="GET" class="flex items-center space-x-2">
                    <select name="period" onchange="this.form.submit()" class="bg-gray-700 text-white rounded-md px-4 py-2">
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Este Mes</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Este Año</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Personalizado</option>
                    </select>
                    <input type="date" name="start_date" value="{{ $customStartDate }}" class="bg-gray-700 text-white rounded-md px-4 py-2 {{ $period != 'custom' ? 'hidden' : '' }}">
                    <input type="date" name="end_date" value="{{ $customEndDate }}" class="bg-gray-700 text-white rounded-md px-4 py-2 {{ $period != 'custom' ? 'hidden' : '' }}">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Filtrar
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-2 text-white">Total Ventas</h3>
                <p class="text-3xl font-bold text-white">{{ $stats['total_sales'] }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-2 text-white">Monto Total (Soles)</h3>
                <p class="text-3xl font-bold text-white">S/. {{ number_format($stats['total_amount_soles'], 2) }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-2 text-white">Monto Total (Dólares)</h3>
                <p class="text-3xl font-bold text-white">$ {{ number_format($stats['total_amount_dollars'], 2) }}</p>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4 text-white">Productos Vendidos</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Cantidad
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Total (Soles)
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Total (Dólares)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['products_sold'] as $product => $data)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    {{ $product }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    {{ $data['quantity'] }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    S/. {{ number_format($data['total_soles'], 2) }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    $ {{ number_format($data['total_dollars'], 2) }}
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

        <div class="bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-4 text-white">Ventas Diarias</h3>
            <canvas id="dailySalesChart" class="w-full h-64"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('dailySalesChart').getContext('2d');
    const dailySales = @json($stats['daily_sales']);

    const dates = Object.keys(dailySales);
    const sales = Object.values(dailySales).map(day => day.count);
    const amountsSoles = Object.values(dailySales).map(day => day.total_soles);
    const amountsDollars = Object.values(dailySales).map(day => day.total_dollars);

    new Chart(ctx, {
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
                    data: amountsSoles,
                    borderColor: 'rgb(153, 102, 255)',
                    tension: 0.1,
                    yAxisID: 'y1'
                },
                {
                    label: 'Monto Total ($)',
                    data: amountsDollars,
                    borderColor: 'rgb(255, 159, 64)',
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
                        text: 'Monto Total',
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
});

// Mostrar/ocultar campos de fecha personalizada en el filtro
document.querySelector('select[name="period"]').addEventListener('change', function() {
    const customDateInputs = document.querySelectorAll('input[type="date"]');
    if (this.value === 'custom') {
        customDateInputs.forEach(input => input.classList.remove('hidden'));
    } else {
        customDateInputs.forEach(input => input.classList.add('hidden'));
    }
});
</script>
@endpush

