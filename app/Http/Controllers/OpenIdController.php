<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Laravel\Passport\Passport;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OpenIdController extends Controller
{
    /**
     * Return information used to automatically discover OpenID options.
     */
    public function discovery(): JsonResponse
    {
        return response()->json([
            'issuer' => secure_url('/'),
            'authorization_endpoint' => route('passport.authorizations.authorize'),
            'token_endpoint' => route('passport.token'),
            'userinfo_endpoint' => route('openid.userinfo'),
            'jwks_uri' => route('openid.jwks'),
            'response_types_supported' => [
                'code',
                'token',
                'code token',
                'none',
            ],
            'subject_types_supported' => [
                'public',
            ],
            'id_token_signing_alg_values_supported' => [
                'RS256',
            ],
            'scopes_supported' => Passport::scopeIds(),
            'token_endpoint_auth_methods_supported' => [
                'client_secret_basic',
                'client_secret_post',
            ],
            'claims_supported' => [
                'email',
                'email_verified',
                'locale',
                'nickname',
                'preferred_username',
                'sub',
              ],
        ], JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    /**
     * Return JSON Web Key Sets.
     *
     * @throws HttpException
     */
    public function jwks(): JsonResponse
    {
        if (File::exists(storage_path('oauth-public.key')) === false) {
            throw new HttpException(JsonResponse::HTTP_NOT_FOUND);
        }

        $keyInfo = openssl_pkey_get_details(openssl_pkey_get_public(File::get(storage_path('oauth-public.key'))));

        return response()->json([
            'keys' => [
                [
                    'kty' => 'RSA',
                    'n' => rtrim(str_replace(['+', '/'], ['-', '_'], base64_encode($keyInfo['rsa']['n'])), '='),
                    'e' => rtrim(str_replace(['+', '/'], ['-', '_'], base64_encode($keyInfo['rsa']['e'])), '='),
                ],
            ],
        ], JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    /**
     * Return user information based on allowed scopes.
     *
     * @throws HttpException
     */
    public function userinfo(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->tokenCan('openid')) {
            throw new HttpException(JsonResponse::HTTP_FORBIDDEN, 'The "openid" scope is required');
        }

        $data = [
            'sub' => $user->id,
        ];

        if ($user->tokenCan('email')) {
            $data = array_merge($data, [
                'email' => $user->email,
                'email_verified' => $user->email_verified_at !== null,
            ]);
        }

        if ($user->tokenCan('profile')) {
            $data = array_merge($data, [
                'nickname' => $user->username,
                'preferred_username' => $user->username,
                'locale' => App::getLocale() === 'pl' ? 'pl-PL' : 'en-US',
            ]);
        }

        return response()->json($data, JsonResponse::HTTP_OK, [], JSON_PRETTY_PRINT);
    }
}
