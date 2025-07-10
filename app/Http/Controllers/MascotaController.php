<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class MascotaController extends Controller
{
    public function selectAllMascotas(Request $request)
    {
        try {
            //$mascotas = Mascota::with('persona')->get();
            $mascotas = Mascota::all();

            return response()->json(['status' => true, 'data' => $mascotas], 200);
        } catch (Exception $e) {
            Log::error('MascotaController@selectAllMascotas: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al listar mascotas', 'line'=> $e->getLine()], 500);
        }
    }

    public function newMascota(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:250',
                'especie' => 'required|string|max:100',
                'raza' => 'nullable|string|max:150',
                'edad' => 'nullable|integer|min:0',
                'persona_id' => 'required|exists:personas,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

             Mascota::create($request->all());

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Mascota creada correctamente'], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('MascotaController@newMascota: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al crear mascota', 'line'=> $e->getLine()], 500);
        }
    }

    public function selectMascota($id)
    {
        try {
            $mascota = Mascota::find($id);

            if (!$mascota) {
                return response()->json(['status' => false, 'message' => 'Mascota no encontrada'], 404);
            }

            return response()->json(['status' => true, 'data' => $mascota], 200);
        } catch (Exception $e) {
            Log::error('MascotaController@selectMascota: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al obtener mascota', 'line'=> $e->getLine()], 500);
        }
    }

    public function updateMascota(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $mascota = Mascota::find($id);

            if (!$mascota) {
                return response()->json(['status' => false, 'message' => 'Mascota no encontrada'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:250',
                'especie' => 'sometimes|required|string|max:100',
                'raza' => 'nullable|string|max:150',
                'edad' => 'nullable|integer|min:0',
                'persona_id' => 'sometimes|required|exists:personas,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $mascota->update($request->all());

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Mascota actualizada correctamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('MascotaController@updateMascota: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al actualizar mascota', 'line'=> $e->getLine()], 500);
        }
    }

    public function deleteMascota($id)
    {
        DB::beginTransaction();
        try {
            $mascota = Mascota::find($id);

            if (!$mascota) {
                return response()->json(['status' => false, 'message' => 'Mascota no encontrada'], 404);
            }

            $mascota->delete();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Mascota eliminada correctamente'], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('MascotaController@deleteMascota: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al eliminar mascota', 'line'=> $e->getLine()], 500);
        }
    }
}
