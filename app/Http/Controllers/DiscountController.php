<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount; //importa el modelo Discount

class DiscountController extends Controller
{
    public function index()
    {
        try {
        $obj = Discount::all(); // Obtiene todos los registros de la tabla discounts
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Discounts retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving discounts: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }   
    }

    public function show($id)
    {
        try {
        $obj = Discount::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Discount retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving discount: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|max:50|unique:discounts,code',
                'discount_type' => 'required|in:percentage,amount',
                'discount_value' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            $obj = Discount::create($validatedData);

            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Discount created successfully'
                ], 201
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating discount: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'sometimes|required|string|max:50|unique:discounts,code,' . $id,
                'discount_type' => 'sometimes|required|in:percentage,amount',
                'discount_value' => 'sometimes|required|numeric|min:0',
                'description' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            $obj = Discount::findOrFail($id);
            $obj->update($validatedData);

            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Discount updated successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error updating discount: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }

    public function destroy($id)
    {
        try {
            $obj = Discount::findOrFail($id);
            $obj->delete();

            return response()->json(
                ['data' => null,
                'status' => 'success',
                'message' => 'Discount deleted successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting discount: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }

    public function search($name)
    {
        try {
            $obj = Discount::where('code', 'LIKE', '%' . $name . '%')->get();
            // 'code', 'LIKE', '%' . $name . '%' busca coincidencias parciales en el campo 'code' que contengan el valor de $name en cualquier posición
            // si quiero una busqueda exacta uso '=' en lugar de 'LIKE' y quito los '%'
            return response()->json(
                ['data' => $obj,
                'status' => 'success',
                'message' => 'Discounts retrieved successfully'
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error searching discounts: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
}
