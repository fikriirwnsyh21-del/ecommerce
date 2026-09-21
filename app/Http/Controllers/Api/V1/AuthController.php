<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::with(['role', 'shop'])->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password yang Anda masukkan salah.',
            ], 422);
        }

        if (! $user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang dinonaktifkan oleh administrator.',
            ], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user),
            ],
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_seller' => ['nullable', 'boolean'],
            'shop_name' => ['required_if:is_seller,true,1', 'nullable', 'string', 'max:100'],
            'shop_city' => ['required_if:is_seller,true,1', 'nullable', 'string', 'max:100'],
        ]);

        $roleSlug = $request->boolean('is_seller') ? 'seller' : 'customer';
        $role = Role::where('slug', $roleSlug)->first();

        $user = User::create([
            'role_id' => $role ? $role->id : 1,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        if ($request->boolean('is_seller')) {
            $shopName = $request->input('shop_name');
            $slug = Str::slug($shopName);
            if (Shop::where('slug', $slug)->exists()) {
                $slug = "{$slug}-".uniqid();
            }

            Shop::create([
                'user_id' => $user->id,
                'name' => $shopName,
                'slug' => $slug,
                'city' => $request->input('shop_city', 'Jakarta'),
                'is_active' => true,
            ]);
        }

        $user->load(['role', 'shop']);
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran akun berhasil.',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user),
            ],
        ], 201);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['role', 'shop']);

        return response()->json([
            'success' => true,
            'message' => 'Profil pengguna berhasil diambil.',
            'data' => new UserResource($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil. Token telah dihapus.',
        ]);
    }
}
