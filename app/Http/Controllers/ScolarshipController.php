<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scolarship; //importa el modelo Scolarship

class ScolarshipController extends Controller
{
    public function index()
    {
        try {
        $obj = Scolarship::all(); // Obtiene todos los registros de la tabla scolarships
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Scolarships retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving scolarships: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function show($id)
    {
        try {
        $obj = Scolarship::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Scolarship retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving scolarship: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'enrollment_id' => 'required|exists:enrollments,id',
                'discount_id' => 'required|exists:discounts,id',
                'approved_by' => 'required|integer',
                'approved_at' => 'required|date',
                'notes' => 'nullable|string',
            ]);

            $obj = Scolarship::create($validatedData); // Crea un nuevo registro en la tabla scolarships
            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Scolarship created successfully'
                ], 201
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating scolarship: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function update(Request $request, $id)
    {
        try {
            // 1. Validamos usando 'sometimes' en todos los campos susceptibles de cambio
            $validatedData = $request->validate([
                'enrollment_id' => 'sometimes|required|exists:enrollments,id',
                'discount_id' => 'sometimes|required|exists:discounts,id',
                'approved_by' => 'sometimes|required|integer',
                'approved_at' => 'sometimes|date',
                'notes' => 'sometimes|nullable|string',
            ]);

            // 2. (Opcional) Verificamos si llegó algún dato válido para actualizar
            if (empty($validatedData)) {
                return response()->json(
                    ['data' => null,
                    'status' => '400',
                    'success' => false,
                    'message' => 'No valid data provided for update'
                    ], 400
                );
            }

            // 3. Buscar el registro existente
            $obj = Scolarship::findOrFail($id);

            // 4. Actualizar el registro con los datos validados
            $obj->update($validatedData);

            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Scolarship updated successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error updating scolarship: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function destroy($id)
    {
        try {
            $obj = Scolarship::findOrFail($id);
            $obj->delete();

            return response()->json(
                ['data' => null,
                'status' => 'success',
                'message' => 'Scolarship deleted successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting scolarship: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
}