@extends('layouts.app')

@section('title', 'Crear Servicio Técnico')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold leading-6">Crear Servicio Técnico</h1>
            <p class="mt-2 text-sm text-gray-400">Registra un nuevo servicio técnico.</p>
        </div>
        <a href="{{ route('technical_services.index') }}" class="text-sm text-gray-400 hover:text-white">
            Volver a Servicios Técnicos
        </a>
    </div>

    <form action="{{ route('technical_services.store') }}" method="POST" class="mt-8">
        @csrf
        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <!-- Información del Cliente -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-white mb-4">Información del Cliente</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="client_name" class="block text-sm font-medium leading-6 text-white">Nombre del Cliente</label>
                        <div class="mt-2">
                            <input type="text" name="client_name" id="client_name" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el nombre del cliente">
                        </div>
                    </div>

                    <div>
                        <label for="client_phone" class="block text-sm font-medium leading-6 text-white">Teléfono del Cliente</label>
                        <div class="mt-2">
                            <input type="text" name="client_phone" id="client_phone" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el teléfono del cliente">
                        </div>
                    </div>

                    <div>
                        <label for="client_dni" class="block text-sm font-medium leading-6 text-white">DNI del Cliente</label>
                        <div class="mt-2">
                            <input type="text" name="client_dni" id="client_dni"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el DNI del cliente">
                        </div>
                    </div>

                    <div>
                        <label for="client_ruc" class="block text-sm font-medium leading-6 text-white">RUC del Cliente</label>
                        <div class="mt-2">
                            <input type="text" name="client_ruc" id="client_ruc"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el RUC del cliente">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Equipo -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-white mb-4">Información del Equipo</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="brand" class="block text-sm font-medium leading-6 text-white">Marca</label>
                        <div class="mt-2">
                            <input type="text" name="brand" id="brand" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese la marca del equipo">
                        </div>
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium leading-6 text-white">Modelo</label>
                        <div class="mt-2">
                            <input type="text" name="model" id="model" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el modelo del equipo">
                        </div>
                    </div>

                    <div>
                        <label for="serial_number" class="block text-sm font-medium leading-6 text-white">Número de Serie</label>
                        <div class="mt-2">
                            <input type="text" name="serial_number" id="serial_number" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el número de serie">
                        </div>
                    </div>

                    <div>
                        <label for="processor" class="block text-sm font-medium leading-6 text-white">Procesador</label>
                        <div class="mt-2">
                            <input type="text" name="processor" id="processor"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el procesador">
                        </div>
                    </div>

                    <div>
                        <label for="ram" class="block text-sm font-medium leading-6 text-white">RAM</label>
                        <div class="mt-2">
                            <input type="text" name="ram" id="ram"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese la RAM">
                        </div>
                    </div>

                    <div>
                        <label for="hard_drive" class="block text-sm font-medium leading-6 text-white">Disco Duro</label>
                        <div class="mt-2">
                            <input type="text" name="hard_drive" id="hard_drive"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el disco duro">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagnóstico y Solución -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-white mb-4">Diagnóstico y Solución</h2>
                <div class="space-y-6">
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium leading-6 text-white">Diagnóstico</label>
                        <div class="mt-2">
                            <textarea name="diagnosis" id="diagnosis" rows="3" required
                                      class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                      placeholder="Ingrese el diagnóstico del equipo"></textarea>
                        </div>
                    </div>

                    <div>
                        <label for="problem" class="block text-sm font-medium leading-6 text-white">Problema</label>
                        <div class="mt-2">
                            <textarea name="problem" id="problem" rows="3" required
                                      class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                      placeholder="Describa el problema del equipo"></textarea>
                        </div>
                    </div>

                    <div>
                        <label for="solution" class="block text-sm font-medium leading-6 text-white">Solución</label>
                        <div class="mt-2">
                            <textarea name="solution" id="solution" rows="3"
                                      class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                      placeholder="Describa la solución aplicada"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-white mb-4">Información Adicional</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="invoice_date" class="block text-sm font-medium leading-6 text-white">Fecha de Facturación</label>
                        <div class="mt-2">
                            <input type="date" name="invoice_date" id="invoice_date"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="guide_number" class="block text-sm font-medium leading-6 text-white">Número de Guía</label>
                        <div class="mt-2">
                            <input type="text" name="guide_number" id="guide_number"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Ingrese el número de guía">
                        </div>
                    </div>

                    <div>
                        <label for="service_price" class="block text-sm font-medium leading-6 text-white">Precio del Servicio</label>
                        <div class="mt-2">
                            <input type="number" name="service_price" id="service_price" step="0.01" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium leading-6 text-white">Estado</label>
                        <div class="mt-2">
                            <select name="status" id="status" required
                                    class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="pending">Pendiente</option>
                                <option value="in_progress">En Progreso</option>
                                <option value="completed">Completado</option>
                                <option value="delivered">Entregado</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="repair_status" class="block text-sm font-medium leading-6 text-white">Estado de Reparación</label>
                        <div class="mt-2">
                            <select name="repair_status" id="repair_status" required
                                    class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="pending">Pendiente</option>
                                <option value="in_progress">En Progreso</option>
                                <option value="repaired">Reparado</option>
                                <option value="unrepairable">No Reparable</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="delivery_status" class="block text-sm font-medium leading-6 text-white">Estado de Entrega</label>
                        <div class="mt-2">
                            <select name="delivery_status" id="delivery_status" required
                                    class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="not_delivered">No Entregado</option>
                                <option value="delivered">Entregado</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="order_date" class="block text-sm font-medium leading-6 text-white">Fecha de Orden</label>
                        <div class="mt-2">
                            <input type="datetime-local" name="order_date" id="order_date" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                        </div>
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
                    Crear Servicio Técnico
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

