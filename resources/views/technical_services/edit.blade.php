@extends('layouts.app')

@section('title', 'Editar Servicio Técnico')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold leading-6 text-white">Editar Servicio Técnico</h1>
            <p class="mt-2 text-sm text-gray-400">Actualiza los detalles del servicio técnico.</p>
        </div>
        <a href="{{ route('technical_service.index') }}" class="text-sm text-gray-400 hover:text-white">
            Volver a Servicios Técnicos
        </a>
    </div>

    @if ($errors->any())
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Por favor corrige los siguientes errores:</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('technical_service.update', $technicalService->id) }}" method="POST" class="mt-8">
        @csrf
        @method('PUT')
        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <div class="space-y-6">
                <div>
                    <label for="client_name" class="block text-sm font-medium leading-6 text-white">Nombre del Cliente</label>
                    <div class="mt-2">
                        <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $technicalService->client_name) }}" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="client_phone" class="block text-sm font-medium leading-6 text-white">Teléfono del Cliente</label>
                    <div class="mt-2">
                        <input type="text" name="client_phone" id="client_phone" value="{{ old('client_phone', $technicalService->client_phone) }}" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="brand" class="block text-sm font-medium leading-6 text-white">Marca</label>
                    <div class="mt-2">
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $technicalService->brand) }}" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium leading-6 text-white">Modelo</label>
                    <div class="mt-2">
                        <input type="text" name="model" id="model" value="{{ old('model', $technicalService->model) }}" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="serial_number" class="block text-sm font-medium leading-6 text-white">Número de Serie</label>
                    <div class="mt-2">
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $technicalService->serial_number) }}" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="problem" class="block text-sm font-medium leading-6 text-white">Problema</label>
                    <div class="mt-2">
                        <textarea name="problem" id="problem" rows="3" required
                                  class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">{{ old('problem', $technicalService->problem) }}</textarea>
                    </div>
                </div>

                <div>
                    <label for="diagnosis" class="block text-sm font-medium leading-6 text-white">Diagnóstico</label>
                    <div class="mt-2">
                        <textarea name="diagnosis" id="diagnosis" rows="3" required
                                  class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">{{ old('diagnosis', $technicalService->diagnosis) }}</textarea>
                    </div>
                </div>

                <div>
                    <label for="solution" class="block text-sm font-medium leading-6 text-white">Solución</label>
                    <div class="mt-2">
                        <textarea name="solution" id="solution" rows="3"
                                  class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">{{ old('solution', $technicalService->solution) }}</textarea>
                    </div>
                </div>

                <div>
                    <label for="service_price" class="block text-sm font-medium leading-6 text-white">Precio del Servicio</label>
                    <div class="mt-2">
                        <input type="number" name="service_price" id="service_price" value="{{ old('service_price', $technicalService->service_price) }}" step="0.01" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium leading-6 text-white">Estado</label>
                    <div class="mt-2">
                        <select name="status" id="status" required
                                class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                            <option value="pending" {{ old('status', $technicalService->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="in_progress" {{ old('status', $technicalService->status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                            <option value="completed" {{ old('status', $technicalService->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="delivered" {{ old('status', $technicalService->status) == 'delivered' ? 'selected' : '' }}>Entregado</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="repair_status" class="block text-sm font-medium leading-6 text-white">Estado de Reparación</label>
                    <div class="mt-2">
                        <select name="repair_status" id="repair_status" required
                                class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                            <option value="pending" {{ old('repair_status', $technicalService->repair_status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="in_progress" {{ old('repair_status', $technicalService->repair_status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                            <option value="repaired" {{ old('repair_status', $technicalService->repair_status) == 'repaired' ? 'selected' : '' }}>Reparado</option>
                            <option value="unrepairable" {{ old('repair_status', $technicalService->repair_status) == 'unrepairable' ? 'selected' : '' }}>No Reparable</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="delivery_status" class="block text-sm font-medium leading-6 text-white">Estado de Entrega</label>
                    <div class="mt-2">
                        <select name="delivery_status" id="delivery_status" required
                                class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                            <option value="not_delivered" {{ old('delivery_status', $technicalService->delivery_status) == 'not_delivered' ? 'selected' : '' }}>No Entregado</option>
                            <option value="delivered" {{ old('delivery_status', $technicalService->delivery_status) == 'delivered' ? 'selected' : '' }}>Entregado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('technical_services.index') }}"
                   class="rounded-md px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Cancelar
                </a>
                <button type="submit"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-black shadow-sm transition-all hover:bg-gray-200">
                    Actualizar Servicio Técnico
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

