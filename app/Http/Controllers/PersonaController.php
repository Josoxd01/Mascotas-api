<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class PersonaController extends Controller
{
    public function selectAll(Request $request)
    {
        try {
            $personas = Persona::all();
            return response()->json(['status' => true, 'data' => $personas], 200);
        } catch (Exception $e) {
            Log::error('PersonaController@selectAll: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al listar personas', 'line'=> $e->getLine()], 500);
        }
    }

    public function newPerson(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:250',
                'email' => 'required|email|max:150|unique:personas',
                'fecha_nacimiento' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Persona creada correctamente'], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('PersonaController@firstNewPerson: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al crear persona', 'line'=> $e->getLine()], 500);
        }
    }

    public function selectPerson(Request $request,$id)
    {
        try {
            $persona = Persona::find($id);
            $incluyeMascotas = $request->query('incluyremascota');
            $persona = Persona::find($id);

            if (!$persona) {
                return response()->json(['status' => false, 'message' => 'Persona no encontrada'], 404);
            }
            if ($incluyeMascotas === 'si') {
                $persona->load('mascotas');
            }
            return response()->json(['status' => true, 'data' => $persona], 200);
        } catch (Exception $e) {
            Log::error('PersonaController@selectPerson: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al obtener persona', 'line'=> $e->getLine()], 500);
        }
    }

    public function updatePerson(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $persona = Persona::find($id);
            if(!isset($persona)){
                return response()->json(['status' => false, 'message' => 'Persona no encontrada'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:250',
                'email' => 'sometimes|required|email|max:150|unique:personas,email,' . $id,
                'fecha_nacimiento' => 'sometimes|required|date',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $persona->update($request->all());

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Persona actualizada correctamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('PersonaController@updatePerson: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al actualizar persona', 'line'=> $e->getLine()], 500);
        }
    }

    public function deletePerson($id)
    {
        DB::beginTransaction();
        try {
            $persona = Persona::find($id);

            if (!$persona) {
                return response()->json(['status' => false, 'message' => 'Persona no encontrada'], 404);
            }

            $persona->delete();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Persona eliminada correctamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('PersonaController@deletePerson: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al eliminar persona', 'line'=> $e->getLine()], 500);
        }
    }
}
