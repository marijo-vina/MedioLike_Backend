<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingGroup; //importa el modelo TrainingGroup

class TrainingGroupController extends Controller
{
    public function index()
    {
        try {
        $obj = TrainingGroup::all(); // Obtiene todos los registros de la tabla training_groups
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Training Groups retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving training groups: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function show($id)
    {
        try {
        $obj = TrainingGroup::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Training Group retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving training group: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'training_id' => 'required|exists:trainings,id', // Valida que exista el id en trainings
                'group_name' => 'required|string|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $obj = TrainingGroup::create($validatedData); // Crea un nuevo registro en la tabla training_groups

            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Training Group created successfully'
                ], 201
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating training group: ' .
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
                'training_id' => 'sometimes|required|exists:trainings,id', // Valida que exista el id en trainings
                'group_name' => 'sometimes|required|string|max:100',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
            ]);

            // 2. (Opcional) Verificamos si llegó algún dato válido para actualizar
            if (empty($validatedData)) {
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'No se enviaron datos para actualizar'
                ], 400);
            }

            $obj = TrainingGroup::findOrFail($id);
            
            // 3. Eloquent es inteligente: update() solo tocará las columnas 
            // que estén dentro del array $validatedData.
            $obj->update($validatedData);

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Training Group actualizado correctamente',
                'data' => $obj
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Es buena práctica capturar el error específico de "No encontrado" (404)
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Training Group no encontrado',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Ocurrió un error al actualizar el Training Group',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
        $obj = TrainingGroup::findOrFail($id); // Busca un registro por su ID
        $obj_temporal = $obj; // Guarda temporalmente el objeto antes de eliminarlo
        $obj->delete(); // Elimina el registro
        return response()->json(
            ['data' => $obj_temporal,
            'status' => 'success',
            'message' => 'Training Group deleted successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting training group: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500

            );
        }
        
    } 
    public function search($name)
    {
        try {
        $obj = TrainingGroup::where('group_name', 'like', '%' . $name . '%')->get(); // Busca registros que coincidan con el nombre
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Training Groups retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(

                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error searching training groups: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
        
}