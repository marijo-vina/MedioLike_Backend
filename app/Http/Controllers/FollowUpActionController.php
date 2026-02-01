<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FollowUpAction; //importa el modelo FollowUpAction

class FollowUpActionController extends Controller
{
    public function index()
    {
        try {
        $obj = FollowUpAction::all(); // Obtiene todos los registros de la tabla follow_up_actions
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Follow-up actions retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving follow-up actions: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }

    public function show($id)
    {
        try {
        $obj = FollowUpAction::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Follow-up action retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving follow-up action: ' .
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
                'action_type' => 'required|in:registered,payment_instructions_sent,scholarship_applied,payment_received,payment_validated,welcome_sent,training_started,training_finished,certificate_delivered',
                'notes' => 'nullable|string',
                'performed_by' => 'nullable|integer',
                'performed_at' => 'nullable|date',
        ]);
            $obj = FollowUpAction::create($validatedData); // Crea un nuevo registro en la tabla follow_up_actions
            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Follow-up action created successfully'
                ], 201
            );
        } catch (\Exception $e) {
            return response()->json(    
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating follow-up action: ' .
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
                'action_type' => 'sometimes|required|in:registered,payment_instructions_sent,scholarship_applied,payment_received,payment_validated,welcome_sent,training_started,training_finished,certificate_delivered',
                'notes' => 'sometimes|nullable|string',
                'performed_by' => 'sometimes|nullable|integer',
                'performed_at' => 'sometimes|nullable|date',
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

            // 3. Buscamos el registro a actualizar
            $obj = FollowUpAction::findOrFail($id);

            // 4. Actualizamos el registro con los datos validados
            $obj->update($validatedData);

            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Follow-up action updated successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error updating follow-up action: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function destroy($id)
    {
        try {
            $obj = FollowUpAction::findOrFail($id);
            $obj->delete();
            return response()->json(
                ['data' => null,
                'status' => 'success',
                'message' => 'Follow-up action deleted successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting follow-up action: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
}