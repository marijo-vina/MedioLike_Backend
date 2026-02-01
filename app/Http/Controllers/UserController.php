<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; //importa el modelo User
class UserController extends Controller
{
    public function index()
    {
        try {
        $obj = User::all(); // Obtiene todos los registros de la tabla users
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'Users retrieved successfully'
            ], 200

        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving users: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function show($id)
    {
        try {
        $obj = User::findOrFail($id); // Busca un registro por su ID
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'User retrieved successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error retrieving user: ' .
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
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

        $obj = User::create($validatedData); // Crea un nuevo registro con los datos del request
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'User created successfully'
            ], 201
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error creating user: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|required|string|min:8',
            ]); //utilizamos 'sometimes' para que los campos sean opcionales
        
        $obj = User::findOrFail($id); // Busca el registro por su ID
        $obj->update($validatedData); // Actualiza el registro con los datos del request
        return response()->json(
            ['data' => $obj,
            'status' => 'success',
            'message' => 'User updated successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error updating user: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
    public function destroy($id)
    {
        try {
        $obj = User::findOrFail($id); // Busca el registro por su ID
        $obj->delete(); // Elimina el registro
        return response()->json(
            ['data' => null,
            'status' => 'success',
            'message' => 'User deleted successfully'
            ], 200
        );
        } catch (\Exception $e) {
            return response()->json(
                ['data' => null,
                'status' => '500',
                'success' => false,
                'message' => 'Error deleting user: ' .
                $e->getMessage() //captura el mensaje de error del sistema
                ], 500
            );
        }
    }
} 