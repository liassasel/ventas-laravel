<?php

namespace App\Http\Controllers;

use App\Models\TechnicalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicalServiceController extends Controller
{
    public function index()
    {
        $services = TechnicalService::with('seller')->latest('order_date')->get();
        return view('technical_services.index', compact('services'));
    }

    public function create()
    {
        return view('technical_services.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'diagnosis' => 'required|string',
            'problem' => 'required|string',
            'service_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed,delivered',
            'repair_status' => 'required|in:pending,in_progress,repaired,unrepairable',
            'delivery_status' => 'required|in:not_delivered,delivered',
            'order_date' => 'required|date',
            'user_id' => 'required|exists:users,id' // Change
        ]);

        $validatedData['seller_id'] = Auth::id();
        
        TechnicalService::create($validatedData);

        return redirect()->route('technical_services.index')->with('success', 'Servicio técnico creado exitosamente.');
    }

    public function show(TechnicalService $technicalService)
    {
        return view('technical_services.show', compact('technicalService'));
    }

    public function edit(TechnicalService $technicalService)
    {
        return view('technical_services.edit', compact('technicalService'));
    }

    public function update(Request $request, TechnicalService $technicalService)
    {
        $validatedData = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'diagnosis' => 'required|string',
            'problem' => 'required|string',
            'service_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed,delivered',
            'repair_status' => 'required|in:pending,in_progress,repaired,unrepairable',
            'delivery_status' => 'required|in:not_delivered,delivered',
            'order_date' => 'required|date',
            'user_id' => 'required|exists:users,id' // Change
        ]);

        $technicalService->update($validatedData);

        return redirect()->route('technical_services.index')->with('success', 'Servicio técnico actualizado exitosamente.');
    }

    public function destroy(TechnicalService $technicalService)
    {
        $technicalService->delete();
        return redirect()->route('technical_services.index')->with('success', 'Servicio técnico eliminado exitosamente.');
    }
}

