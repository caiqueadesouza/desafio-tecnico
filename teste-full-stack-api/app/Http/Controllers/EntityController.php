<?php

namespace App\Http\Controllers;

use App\Entity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EntityRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $busca = $request->filter;
            $buscaFormatada = str_replace(' ', '%', $busca);

            $orderBy = trim($request->get('orderBy'));
            $orderBy = !empty($orderBy) ? $orderBy : 'fantasy_name';

            $allowedOrderBy = ['fantasy_name', 'corporate_reason', 'cnpj'];
            if (!in_array($orderBy, $allowedOrderBy)) {
                $orderBy = 'fantasy_name';
            }

            $order = strtolower($request->get('order', 'asc'));
            $order = $order === 'desc' ? 'desc' : 'asc';

            $query = Entity::with('specialties', 'regional');

            if (!empty($buscaFormatada)) {
                $query->where(function ($q) use ($buscaFormatada) {
                    $q->where('fantasy_name', 'like', '%' . $buscaFormatada . '%')
                        ->orWhere('corporate_reason', 'like', '%' . $buscaFormatada . '%')
                        ->orWhere('cnpj', 'like', '%' . $buscaFormatada . '%');
                });
            }

            $query->orderBy($orderBy, $order);

            return response()->json($query->paginate(10));
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar entidades.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntityRequest $request): JsonResponse
    {
        try {
            $entity = Entity::create($request->except('specialties'));

            $specialtyId = collect($request->specialties)->pluck('id');
            $entity->specialties()->sync($specialtyId);

            return response()->json($entity->load('specialties'));
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erro no banco de dados.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro inesperado.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $entity = Entity::with('specialties')->findOrFail($id);
            return response()->json($entity);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erro no banco de dados.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro inesperado.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EntityRequest $request, $id)
    {
        try {
            $entity = Entity::findOrFail($id);

            $entity->update($request->except('specialties'));

            if ($request->has('specialties')) {
                $specialtyId = collect($request->specialties)->pluck('id');
                $entity->specialties()->sync($specialtyId);
            }

            return response()->json($entity->load('specialties'));
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erro no banco de dados.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro inesperado.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $entity = Entity::findOrFail($id);
            $entity->delete();
            return response()->json($entity);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erro no banco de dados.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro inesperado.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
