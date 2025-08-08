<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\EntityResource;
use App\Models\Entity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Entity::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        // Filter by parent
        if ($request->has('parent_id')) {
            if ($request->get('parent_id') === 'null') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->get('parent_id'));
            }
        }

        // Filter by type
        if ($request->has('type_id')) {
            $query->where('type_id', $request->get('type_id'));
        }

        // Include relationships
        if ($request->boolean('with_parent')) {
            $query->with('parent');
        }

        if ($request->boolean('with_children')) {
            $query->with('children');
        }

        if ($request->boolean('with_type')) {
            $query->with('type');
        }

        if ($request->boolean('with_children_count')) {
            $query->withCount('children');
        }

        if ($request->boolean('with_usuarios_fiscalizacion')) {
            $query->with('usuariosFiscalizacion');
        }

        if ($request->boolean('with_usuarios_fiscalizacion_count')) {
            $query->withCount('usuariosFiscalizacion');
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $entities = $query->paginate($perPage);

        return EntityResource::collection($entities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntityRequest $request): JsonResponse
    {
        // Validate that parent_id doesn't create circular reference
        if ($request->parent_id) {
            $this->validateNoCircularReference($request->parent_id, null);
        }

        $entity = Entity::create($request->validated());

        return response()->json([
            'message' => 'Entidad creada exitosamente.',
            'data' => new EntityResource($entity->load(['parent', 'type']))
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Entity $entity): JsonResponse
    {
        // Load relationships if requested
        $with = [];
        if ($request->boolean('with_parent')) $with[] = 'parent';
        if ($request->boolean('with_children')) $with[] = 'children';
        if ($request->boolean('with_type')) $with[] = 'type';
        if ($request->boolean('with_usuarios_fiscalizacion')) $with[] = 'usuariosFiscalizacion';

        if (!empty($with)) {
            $entity->load($with);
        }

        return response()->json([
            'data' => new EntityResource($entity)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntityRequest $request, Entity $entity): JsonResponse
    {
        // Validate that parent_id doesn't create circular reference
        if ($request->has('parent_id') && $request->parent_id) {
            $this->validateNoCircularReference($request->parent_id, $entity->id);
        }

        $entity->update($request->validated());

        return response()->json([
            'message' => 'Entidad actualizada exitosamente.',
            'data' => new EntityResource($entity->load(['parent', 'type']))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity): JsonResponse
    {
        $childrenCount = $entity->children()->count();
        $usuariosCount = $entity->usuariosFiscalizacion()->count();
        
        if ($childrenCount > 0) {
            return response()->json([
                'message' => 'No se puede eliminar la entidad porque tiene entidades hijas asociadas.',
                'children_count' => $childrenCount
            ], 422);
        }

        if ($usuariosCount > 0) {
            return response()->json([
                'message' => 'No se puede eliminar la entidad porque tiene usuarios de fiscalización asociados.',
                'usuarios_count' => $usuariosCount
            ], 422);
        }

        $entity->delete();

        return response()->json([
            'message' => 'Entidad eliminada exitosamente.'
        ]);
    }

    /**
     * Get entity hierarchy (tree structure).
     */
    public function hierarchy(Request $request): JsonResponse
    {
        $query = Entity::whereNull('parent_id')->with(['children.children.children', 'type']);

        if ($request->has('type_id')) {
            $query->where('type_id', $request->get('type_id'));
        }

        $rootEntities = $query->get();

        return response()->json([
            'data' => EntityResource::collection($rootEntities)
        ]);
    }

    /**
     * Validate that setting parent_id doesn't create circular reference.
     */
    private function validateNoCircularReference($parentId, $entityId): void
    {
        if ($parentId == $entityId) {
            abort(422, 'Una entidad no puede ser padre de sí misma.');
        }

        if ($entityId) {
            $parent = Entity::find($parentId);
            $ancestors = $parent ? $parent->ancestors() : collect();
            
            if ($ancestors->contains('id', $entityId)) {
                abort(422, 'No se puede crear una referencia circular en la jerarquía de entidades.');
            }
        }
    }
}