<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntityTypeRequest;
use App\Http\Requests\UpdateEntityTypeRequest;
use App\Http\Resources\EntityTypeResource;
use App\Models\EntityType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EntityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = EntityType::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Include entities count
        if ($request->boolean('with_entities_count')) {
            $query->withCount('entities');
        }

        // Include entities
        if ($request->boolean('with_entities')) {
            $query->with('entities');
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $entityTypes = $query->paginate($perPage);

        return EntityTypeResource::collection($entityTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntityTypeRequest $request): JsonResponse
    {
        $entityType = EntityType::create($request->validated());

        return response()->json([
            'message' => 'Tipo de entidad creado exitosamente.',
            'data' => new EntityTypeResource($entityType)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, EntityType $entityType): JsonResponse
    {
        // Load relationships if requested
        if ($request->boolean('with_entities')) {
            $entityType->load('entities');
        }

        return response()->json([
            'data' => new EntityTypeResource($entityType)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntityTypeRequest $request, EntityType $entityType): JsonResponse
    {
        $entityType->update($request->validated());

        return response()->json([
            'message' => 'Tipo de entidad actualizado exitosamente.',
            'data' => new EntityTypeResource($entityType)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EntityType $entityType): JsonResponse
    {
        $entitiesCount = $entityType->entities()->count();
        
        if ($entitiesCount > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el tipo de entidad porque tiene entidades asociadas.',
                'entities_count' => $entitiesCount
            ], 422);
        }

        $entityType->delete();

        return response()->json([
            'message' => 'Tipo de entidad eliminado exitosamente.'
        ]);
    }
}