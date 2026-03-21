<?php
namespace App\Http\Controllers\API\v1;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{Auth, Hash};

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $user  = Auth::user();
        $token = $user->createToken('api-token', ['*'], now()->addDays(30))->plainTextToken;
        $user->update(['last_login_at' => now()]);

        return response()->json(['token' => $token, 'user' => $user->load('organization')]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('creditScore', 'organization'));
    }
}

