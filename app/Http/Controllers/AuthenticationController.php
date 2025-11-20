<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\TokenResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{

    private AuthService $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $requestData = $request->only(['email', 'password']);

        return $this->ok(TokenResource::make($this->authService->login($requestData)));
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {

        $this->authService->logout();

        return $this->ok(['message' => 'logout_success']);
    }
}
