<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioFiscalizacionRequest;
use App\Http\Requests\UpdateUsuarioFiscalizacionRequest;
use App\Http\Requests\UpdateUsuarioProfileRequest;
use App\Http\Requests\UpdateUsuarioPasswordRequest;
use App\Http\Requests\AdminUpdatePasswordRequest;
use App\Http\Requests\LoginUsuarioFiscalizacionRequest;
use App\Http\Resources\UsuarioFiscalizacionResource;
use App\Models\UsuarioFiscalizacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UsuarioFiscalizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = UsuarioFiscalizacion::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cellphone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by distribucion
        if ($request->has('distribucion_id')) {
            $query->where('distribucion_id', $request->get('distribucion_id'));
        }

        // Filter by entity
        if ($request->has('entity_id')) {
            $query->where('entity_id', $request->get('entity_id'));
        }

        // Include distribucion
        if ($request->boolean('with_distribucion')) {
            $query->with('distribucion');
        }

        // Include entity
        if ($request->boolean('with_entity')) {
            $query->with('entity');
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $usuarios = $query->paginate($perPage);

        return UsuarioFiscalizacionResource::collection($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsuarioFiscalizacionRequest $request): JsonResponse
    {
        $usuario = UsuarioFiscalizacion::create($request->validated());

        return response()->json([
            'message' => 'Usuario creado exitosamente.',
            'data' => new UsuarioFiscalizacionResource($usuario->load(['distribucion', 'entity']))
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, UsuarioFiscalizacion $usuarios_fiscalizacion): JsonResponse
    {
        // Load relationships if requested
        if ($request->boolean('with_distribucion')) {
            $usuarios_fiscalizacion->load('distribucion');
        }

        if ($request->boolean('with_entity')) {
            $usuarios_fiscalizacion->load('entity');
        }

        return response()->json([
            'data' => new UsuarioFiscalizacionResource($usuarios_fiscalizacion->load(['distribucion', 'entity']))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioFiscalizacionRequest $request, UsuarioFiscalizacion $usuarios_fiscalizacion): JsonResponse
    {
        $usuarios_fiscalizacion->update($request->validated());

        return response()->json([
            'message' => 'Usuario actualizado exitosamente.',
            'data' => new UsuarioFiscalizacionResource($usuarios_fiscalizacion->load(['distribucion', 'entity']))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsuarioFiscalizacion $usuarios_fiscalizacion): JsonResponse
    {
        $usuarios_fiscalizacion->delete();

        return response()->json([
            'message' => 'Usuario eliminado exitosamente.'
        ]);
    }

    /**
     * Toggle user status between active and inactive.
     */
    public function toggleStatus(UsuarioFiscalizacion $usuarioFiscalizacion): JsonResponse
    {
        $newStatus = $usuarioFiscalizacion->status === 'active' ? 'inactive' : 'active';
        $usuarioFiscalizacion->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'activado' : 'desactivado';

        return response()->json([
            'message' => "Usuario {$message} exitosamente.",
            'data' => new UsuarioFiscalizacionResource($usuarioFiscalizacion)
        ]);
    }

    /**
     * Update user profile (without password).
     */
    public function updateProfile(UpdateUsuarioProfileRequest $request, UsuarioFiscalizacion $usuarioFiscalizacion): JsonResponse
    {
        $usuarioFiscalizacion->update($request->validated());

        return response()->json([
            'message' => 'Perfil actualizado exitosamente.',
            'data' => new UsuarioFiscalizacionResource($usuarioFiscalizacion->load(['distribucion', 'entity']))
        ]);
    }

    /**
     * Update user password.
     */
    public function updatePassword(UpdateUsuarioPasswordRequest $request, UsuarioFiscalizacion $usuarioFiscalizacion): JsonResponse
    {
        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $usuarioFiscalizacion->password)) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta.',
                'errors' => [
                    'current_password' => ['La contraseña actual es incorrecta.']
                ]
            ], 422);
        }

        // Actualizar contraseña
        $usuarioFiscalizacion->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada exitosamente.',
            'data' => new UsuarioFiscalizacionResource($usuarioFiscalizacion->load(['distribucion', 'entity']))
        ]);
    }

    /**
     * Admin: Reset user password (no current password required).
     */
    public function resetPassword(AdminUpdatePasswordRequest $request, UsuarioFiscalizacion $usuarioFiscalizacion): JsonResponse
    {
        // Actualizar contraseña directamente (sin verificar la actual)
        $usuarioFiscalizacion->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Contraseña restablecida exitosamente por el administrador.',
            'data' => new UsuarioFiscalizacionResource($usuarioFiscalizacion->load(['distribucion', 'entity'])),
            'admin_action' => true
        ]);
    }

    /**
     * Login user with email and password to get user data.
     */
    public function login(LoginUsuarioFiscalizacionRequest $request): JsonResponse
    {
        // Buscar usuario por email
        $usuario = UsuarioFiscalizacion::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json([
                'message' => 'Las credenciales proporcionadas son incorrectas.',
                'errors' => [
                    'email' => ['Las credenciales proporcionadas son incorrectas.']
                ]
            ], 401);
        }

        // Verificar si el usuario está activo
        if ($usuario->status !== 'active') {
            return response()->json([
                'message' => 'La cuenta de usuario está inactiva.',
                'errors' => [
                    'status' => ['La cuenta de usuario está inactiva.']
                ]
            ], 403);
        }

        return response()->json([
            'message' => 'Login exitoso.',
            'data' => new UsuarioFiscalizacionResource($usuario->load(['distribucion', 'entity']))
        ]);
    }
}
