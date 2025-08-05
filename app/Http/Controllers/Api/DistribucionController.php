<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDistribucionRequest;
use App\Http\Requests\UpdateDistribucionRequest;
use App\Http\Resources\DistribucionResource;
use App\Models\Distribucion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DistribucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Distribucion::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Include usuarios count
        if ($request->boolean('with_usuarios_count')) {
            $query->withCount('usuarios');
        }

        // Include usuarios
        if ($request->boolean('with_usuarios')) {
            $query->with('usuarios');
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $distribuciones = $query->paginate($perPage);

        return DistribucionResource::collection($distribuciones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistribucionRequest $request): JsonResponse
    {
        $distribucion = Distribucion::create($request->validated());

        return response()->json([
            'message' => 'Distribución creada exitosamente.',
            'data' => new DistribucionResource($distribucion)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Distribucion $distribucione): JsonResponse
    {
        // Load relationships if requested
        if ($request->boolean('with_usuarios')) {
            $distribucione->load('usuarios');
        }

        return response()->json([
            'data' => new DistribucionResource($distribucione)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistribucionRequest $request, Distribucion $distribucione): JsonResponse
    {
        $distribucione->update($request->validated());

        return response()->json([
            'message' => 'Distribución actualizada exitosamente.',
            'data' => new DistribucionResource($distribucione)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Distribucion $distribucion): JsonResponse
    {
        $usuariosCount = $distribucion->usuarios()->count();
        
        if ($usuariosCount > 0) {
            return response()->json([
                'message' => 'No se puede eliminar la distribución porque tiene usuarios asociados.',
                'usuarios_count' => $usuariosCount
            ], 422);
        }

        $distribucion->delete();

        return response()->json([
            'message' => 'Distribución eliminada exitosamente.'
        ]);
    }
}