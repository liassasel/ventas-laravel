@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div class="flex flex-wrap -mx-4 mb-8">
            <div class="w-full md:w-1/5 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Ventas Totales</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalSales ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/5 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Productos</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalProducts ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/5 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Proveedores</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalSuppliers ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/5 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Usuarios</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
            <div class="w-full md:w-1/5 px-4 mb-4">
                <div class="bg-gray-800 rounded-lg shadow-md p-6 h-full">
                    <h3 class="text-lg font-semibold mb-2 text-white">Servicios Técnicos</h3>
                    <p class="text-3xl font-bold text-white">{{ $totalServices ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4 text-white">Estadísticas de Ventas</h3>
            <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
                <div class="flex flex-wrap space-x-4 space-y-2">
                    <select name="store_id" class="rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all" {{ $selectedStore == 'all' ? 'selected' : '' }}>Todas las tiendas</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ $selectedStore == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                    <select name="user_id" class="rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="all" {{ $selectedUser == 'all' ? 'selected' : '' }}>Todos los usuarios</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $selectedUser == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <select name="period" class="rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="day" {{ $selectedPeriod == 'day' ? 'selected' : '' }}>Hoy</option>
                        <option value="week" {{ $selectedPeriod == 'week' ? 'selected' : '' }}>Esta semana</option>
                        <option value="month" {{ $selectedPeriod == 'month' ? 'selected' : '' }}>Este mes</option>
                        <option value="custom" {{ $customStartDate && $customEndDate ? 'selected' : '' }}>Personalizado</option>
                    </select>
                    <input type="date" name="start_date" value="{{ $customStartDate }}" class="rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $selectedPeriod != 'custom' ? 'hidden' : '' }}">
                    <input type="date" name="end_date" value="{{ $customEndDate }}" class="rounded-md border-gray-700 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $selectedPeriod != 'custom' ? 'hidden' : '' }}">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Filtrar
                    </button>
                </div>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-lg font-semibold mb-2 text-white">Ventas por Tienda</h4>
                    <canvas id="salesByStoreChart"></canvas>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-2 text-white">Ventas por Usuario</h4>
                    <canvas id="salesByUserChart"></canvas>
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
                                Monto Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    {{ $product['name'] }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    {{ $product['total_quantity'] }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-700 bg-gray-800 text-sm text-white">
                                    S/. {{ number_format($product['total_amount'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4 text-white">Productos Más Vendidos por Tienda</h3>
            <div class="space-y-4">
                @foreach($topProductsByStore as $storeName => $products)
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
                                            Monto Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td class="px-5 py-5 border-b border-gray-600 bg-gray-700 text-sm text-white">
                                                {{ $product['name'] }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-600 bg-gray-700 text-sm text-white">
                                                {{ $product['total_quantity'] }}
                                            </td>
                                            <td class="px-5 py-5 border-b border-gray-600 bg-gray-700 text-sm text-white">
                                                S/. {{ number_format($product['total_amount'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userStatistics as $stat)
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ventas por Tienda
        new Chart(document.getElementById('salesByStoreChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($salesByStore->pluck('name')) !!},
                datasets: [{
                    label: 'Total de Ventas',
                    data: {!! json_encode($salesByStore->pluck('total_sales')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
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

        // Ventas por Usuario
        new Chart(document.getElementById('salesByUserChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($userStatistics->pluck('name')) !!},
                datasets: [{
                    label: 'Total de Ventas',
                    data: {!! json_encode($userStatistics->pluck('total_sales')) !!},
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
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

    // Mostrar/ocultar campos de fecha personalizada
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

