<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment; //importa el modelo Enrollment

class EnrollmentController extends Controller
{
    public function index()
    {
        try {
        $obj = Enrollment::all(); // Obtiene todos los registros de la tabla enrollments
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Enrollments retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving enrollments: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function show($id)
    {
        try {
        $obj = Enrollment::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Enrollment retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving enrollment: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'training_group_id' => 'required|exists:training_groups,id',
                'enrolled_at' => 'required|date',
            ]);

            $obj = Enrollment::create($validatedData); // Crea un nuevo registro con los datos del request
            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Enrollment created successfully'
                ], 201
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating enrollment: ' .
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
                'user_id' => 'sometimes|required|exists:users,id',
                'training_group_id' => 'sometimes|required|exists:training_groups,id',
                'enrolled_at' => 'sometimes|required|date',
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
            $obj = Enrollment::findOrFail($id);

            // 4. Actualizar el registro con los datos validados
            $obj->update($validatedData);

            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Enrollment updated successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error updating enrollment: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function destroy($id)
    {
        try {
        $obj = Enrollment::findOrFail($id); // Busca un registro por su ID
        $obj_temporal = $obj; // Guarda temporalmente el objeto antes de eliminarlo
        $obj->delete(); // Elimina el registro encontrado
        return response()->json(
            ['data' => $obj_temporal,
            'status' => 'success',
            'message' => 'Enrollment deleted successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting enrollment: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
}
