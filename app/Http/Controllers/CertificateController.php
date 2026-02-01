<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate; //importa el modelo Certificate

class CertificateController extends Controller
{
    public function index()
    {
        try {
        $obj = Certificate::all(); // Obtiene todos los registros de la tabla certificates
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Certificates retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving certificates: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function show($id)
    {
        try {
        $obj = Certificate::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Certificate retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving certificate: ' .
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
                'certificate_code' => 'required|string|max:255|unique:certificates,certificate_code',
                'certificate_path' => 'required|string|max:255',
                'delivered_at' => 'required|date',
            ]);

            $obj = Certificate::create($validatedData); // Crea un nuevo registro con los datos del request
            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Certificate created successfully'
                ], 201
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating certificate: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function update(Request $request, $id)
    {   
        try {
            $validatedData = $request->validate([
                'enrollment_id' => 'sometimes|required|exists:enrollments,id',
                'certificate_code' => 'sometimes|required|string|max:255|unique:certificates,certificate_code,' . $id,
                'certificate_path' => 'sometimes|required|string|max:255',
                'delivered_at' => 'sometimes|required|date',
            ]);

            // Verificamos si llegó algún dato válido para actualizar
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
            $obj = Certificate::findOrFail($id);

            // 4. Actualizar el registro con los datos validados
            $obj->update($validatedData);

            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Certificate updated successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error updating certificate: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function destroy($id)
    {
        try {
            $obj = Certificate::findOrFail($id);
            $obj->delete();

            return response()->json(
                ['data' => null,
                'status' => 'success',
                'message' => 'Certificate deleted successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting certificate: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
}
