<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment; //importa el modelo Payment

class PaymentController extends Controller
{
    public function index()
    {
        try {
        $obj = Payment::all(); // Obtiene todos los registros de la tabla payments
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Payments retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving payments: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function show($id)
    {
        try {
        $obj = Payment::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Payment retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving payment: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'enrollment_id'    => 'required|exists:enrollments,id',
                'payment_method'   => 'required|in:transfer,cash,card', 
                'amount'           => 'required|numeric|min:0',
                'payment_reference'=> 'nullable|string|max:150|unique:payments,payment_reference',
                'receipt_path'     => 'nullable|string|max:255',
                'payment_date'     => 'required|date',
                'validated_at'     => 'nullable|date',
                'validated_by'     => 'nullable|integer',
            ]);
            $obj = Payment::create($validatedData); // Crea un nuevo registro en la tabla payments
            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Payment created successfully'
                ], 201
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating payment: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'enrollment_id'    => 'sometimes|required|exists:enrollments,id',
                'payment_method'   => 'sometimes|required|in:transfer,cash,card', 
                'amount'           => 'sometimes|required|numeric|min:0',
                'payment_reference'=> 'sometimes|nullable|string|max:150|unique:payments,payment_reference,' . $id,
                'receipt_path'     => 'sometimes|nullable|string|max:255',
                'payment_date'     => 'sometimes|required|date',
                'validated_at'     => 'sometimes|nullable|date',
                'validated_by'     => 'sometimes|nullable|integer',
            ]);
            $obj = Payment::findOrFail($id); // Busca el registro por su ID
            $obj->update($validatedData); // Actualiza el registro con los datos validados
            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Payment updated successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error updating payment: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function destroy($id)
    {
        try {
            $obj = Payment::findOrFail($id); // Busca el registro por su ID
            $obj->delete(); // Elimina el registro
            return response()->json(
                ['data' => null,
                'status' => 'success',
                'message' => 'Payment deleted successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting payment: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    } 
}
