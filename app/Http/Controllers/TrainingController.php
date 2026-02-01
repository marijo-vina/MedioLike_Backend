<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training; //importa el modelo Training

class TrainingController extends Controller
{
    public function index()
    {
        try {
        $obj = Training::all(); // Obtiene todos los registros de la tabla trainings
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Trainings retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving trainings: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function show($id)
    {
        try {
        $obj = Training::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Training retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving training: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

        $obj = Training::create($validatedData); // Crea un nuevo registro con los datos del request
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Training created successfully'
            ], 201
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating training: ' .
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
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
            ]);

            // 2. (Opcional) Verificamos si llegó algún dato válido para actualizar
            if (empty($validatedData)) {
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'No se enviaron datos para actualizar'
                ], 400);
            }

            $obj = Training::findOrFail($id);
            
            // 3. Eloquent es inteligente: update() solo tocará las columnas 
            // que estén dentro del array $validatedData.
            $obj->update($validatedData);

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Entrenamiento actualizado correctamente',
                'data' => $obj
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Es buena práctica capturar el error específico de "No encontrado" (404)
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Entrenamiento no encontrado',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Ocurrió un error al actualizar el entrenamiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
        $obj = Training::findOrFail($id); // Busca un registro por su ID
        $obj_temporal = $obj; // Guarda temporalmente el objeto antes de eliminarlo
        $obj->delete(); // Elimina el registro
        return response()->json(
            ['data' => $obj_temporal,
            'status' => 'success',
            'message' => 'Training deleted successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting training: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500

            );
        }
        
    } 
    public function search($name)
    {
        try {
        $obj = Training::where('name', 'like', '%' . $name . '%')->get(); // Busca registros que coincidan con el nombre
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Trainings retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(

                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error searching trainings: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
        
}