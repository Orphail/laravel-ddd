<?php

namespace Src\Auth\Presentation\HTTP;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Src\Auth\Domain\AuthInterface;
use Src\Common\Infrastructure\Laravel\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private AuthInterface $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $username = $request->get('username');
            $password = $request->get('password');
            $credentials = ['username' => strtolower($username), 'password' => $password];
            $validator = Validator::make($credentials, [
                'username' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $token = $this->auth->login($credentials);
            return $this->respondWithToken($token);
        } catch (ValidationException $validationException) {
            return response()->json($validationException->errors(), Response::HTTP_BAD_REQUEST);
        } catch (AuthenticationException) {
            return response()->json([
                "meta" => [
                    "success" =>
                    false, "errors" => [
                        "Password incorrect for: $username"
                    ]
                ]
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Get the authenticated UserEloquentModel.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json($this->auth->me()->toArray());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->auth->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            $token = $this->auth->refresh();
        } catch (AuthenticationException $e) {
            return response()->json(['status' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        /*
        {
            "meta": { "success":
            true,"errors": []
            },
            "data": {
            "token": "TOOOOOKEN",
            "minutes_to_expire": 1440
            }
        }
        */
        return response()->json([
            "meta" => ["success" => true, "errors" => []],
            "data" =>  [
                "token" => $token,
                "minutes_to_expire" => 1440
            ]
        ]);
        // return response()->json([
        //     'accessToken' => $token,
        //     'token_type' => 'bearer',
        //     'expires_in' => config('jwt.ttl') * 1,
        // ]);
    }
}
