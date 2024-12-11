<?php

namespace App\Http\Controllers;

use App\Models\TechnicalService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TechnicalServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = TechnicalService::with('seller')->latest('order_date');

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('order_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('order_date', '<=', $request->end_date);
        }

        // Filter by seller if provided
        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }

        $services = $query->get();
        
        // Get all technicians (users who are either admin or technician)
        $sellers = User::where('is_admin', true)
                      ->orWhere('is_technician', true)
                      ->get();

        return view('technical_services.index', compact('services', 'sellers'));
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
            'client_dni' => 'nullable|string|max:20',
            'client_ruc' => 'nullable|string|max:20',
            'invoice_date' => 'nullable|date',
            'guide_number' => 'nullable|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'processor' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'hard_drive' => 'nullable|string|max:255',
            'diagnosis' => 'required|string',
            'problem' => 'required|string',
            'solution' => 'nullable|string',
            'service_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed,delivered',
            'repair_status' => 'required|in:pending,in_progress,repaired,unrepairable',
            'delivery_status' => 'required|in:not_delivered,delivered',
            'order_date' => 'required|date',
        ]);

        // Add seller_id to the validated data
        $validatedData['seller_id'] = Auth::id();

        try {
            // For debugging: Log the data being saved
            Log::info('Attempting to create technical service with data:', $validatedData);
            
            $technicalService = TechnicalService::create($validatedData);
            
            Log::info('Technical service created successfully with ID: ' . $technicalService->id);
            
            return redirect()
                ->route('technical_services.index')
                ->with('success', 'Servicio técnico creado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error creating technical service: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Hubo un error al crear el servicio técnico. Por favor, inténtelo de nuevo.');
        }
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
            'client_dni' => 'nullable|string|max:20',
            'client_ruc' => 'nullable|string|max:20',
            'invoice_date' => 'nullable|date',
            'guide_number' => 'nullable|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'processor' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'hard_drive' => 'nullable|string|max:255',
            'diagnosis' => 'required|string',
            'problem' => 'required|string',
            'solution' => 'nullable|string',
            'service_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed,delivered',
            'repair_status' => 'required|in:pending,in_progress,repaired,unrepairable',
            'delivery_status' => 'required|in:not_delivered,delivered',
            'order_date' => 'nullable|date',
        ]);

        try {
            Log::info('Attempting to update technical service ID ' . $technicalService->id);
            
            $technicalService->update($validatedData);
            
            Log::info('Technical service updated successfully');
            
            return redirect()
                ->route('technical_services.index')
                ->with('success', 'Servicio técnico actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error updating technical service: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Hubo un error al actualizar el servicio técnico. Por favor, inténtelo de nuevo.');
        }
    }

    public function destroy(TechnicalService $technicalService)
    {
        try {
            Log::info('Attempting to delete technical service ID ' . $technicalService->id);
            
            $technicalService->delete();
            
            Log::info('Technical service deleted successfully');
            
            return redirect()
                ->route('technical_services.index')
                ->with('success', 'Servicio técnico eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error deleting technical service: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Hubo un error al eliminar el servicio técnico. Por favor, inténtelo de nuevo.');
        }
    }
}

