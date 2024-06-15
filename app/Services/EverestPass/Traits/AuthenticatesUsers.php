<?php

namespace App\Services\EverestPass\Traits;

use App\Services\EverestPass\Exceptions\AuthenticationException;
use gnupg as GnuPG;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

trait AuthenticatesUsers
{
    public const SESSION_CACHE_KEY = 'everestpass.passbolt_session';
    public const CSRF_TOKEN_CACHE_KEY = 'everestpass.csrf_token';

    private GnuPG $gpg;
    private ?string $csrfToken;
    private ?string $sessionId;

    private array $privateKey = [
        'keydata' => '',
        'info' => [],
        'passphrase' => null,
    ];

    private array $serverKey = [
        'keydata' => '',
        'info' => [],
    ];

    /**
     * @throws AuthenticationException
     * @throws RequestException
     */
    public function authenticate(): void
    {
        if (Cache::has(self::SESSION_CACHE_KEY) && Cache::has(self::CSRF_TOKEN_CACHE_KEY)) {
            $this->sessionId = Cache::get(self::SESSION_CACHE_KEY);
            $this->csrfToken = Cache::get(self::CSRF_TOKEN_CACHE_KEY);
            return;
        }

        $this->initKeyring();

        $this->stage0();
        $this->stage1B($this->stage1A());

        Cache::set(self::SESSION_CACHE_KEY, $this->sessionId, config('services.everestpass.session_max_lifetime', 1440));
    }

    protected function getCookies(bool $withCsrfToken = false): string
    {
        $cookies = 'passbolt_session=' . $this->sessionId . '; path=/; HttpOnly;';

        if ($withCsrfToken) {
            $cookies .= ' csrfToken=' . $this->csrfToken;
        }

        return $cookies;
    }

    protected function extractCsrfTokenMiddleware(ResponseInterface $response): ResponseInterface
    {
        $cookieHeader = $response->getHeaderLine('Set-Cookie');

        if (Str::contains($cookieHeader, 'csrfToken=')) {
            $this->csrfToken = Str::betweenFirst($cookieHeader, 'csrfToken=', ';');

            Cache::set(self::CSRF_TOKEN_CACHE_KEY, $this->csrfToken, config('services.everestpass.session_max_lifetime', 1440));
        }

        return $response;
    }

    /**
     * @throws AuthenticationException
     * @throws RequestException
     */
    private function stage0(): string
    {
        $token = $this->generateGpgAuthToken();

        $this->gpg->addEncryptKey($this->serverKey['info']['fingerprint']);

        $encryptedToken = $this->gpg->encrypt($token);

        $response = $this->client->post('/auth/verify.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->privateKey['info']['fingerprint'],
                    'server_verify_token' => $encryptedToken,
                ],
            ],
        ]);

        $response->throwUnlessStatus(Response::HTTP_OK);

        $retrievedToken = $response->header('X-GPGAuth-Verify-Response');

        if ($retrievedToken !== $token) {
            throw new AuthenticationException('Stage 0: Tokens mismatch');
        }

        return $token;
    }

    /**
     * @throws AuthenticationException
     * @throws RequestException
     */
    private function stage1A(): string
    {
        $response = $this->client->post('/auth/login.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->privateKey['info']['fingerprint'],
                ],
            ],
        ]);

        $response->throwUnlessStatus(Response::HTTP_OK);

        $encryptedToken = $response->header('X-GPGAuth-User-Auth-Token');
        $encryptedToken = stripslashes(urldecode($encryptedToken));

        $this->gpg->addDecryptKey($this->privateKey['info']['fingerprint'], $this->privateKey['passphrase']);

        $decryptedToken = '';
        $verify = $this->gpg->decryptVerify($encryptedToken, $decryptedToken);

        if ($verify[0]['fingerprint'] !== $this->serverKey['info']['fingerprint']) {
            throw new AuthenticationException('Stage 1A: Signature mismatch');
        }

        return $decryptedToken;
    }

    /**
     * @throws AuthenticationException
     * @throws RequestException
     */
    private function stage1B(string $token): void
    {
        $response = $this->client->post('/auth/login.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->privateKey['info']['fingerprint'],
                    'user_token_result' => $token,
                ],
            ],
        ]);

        $response->throwUnlessStatus(Response::HTTP_OK);

        $progress = $response->header('X-GPGAuth-Progress');
        $authenticated = $response->header('X-GPGAuth-Authenticated');

        $success = $progress === 'complete' && $authenticated === 'true';

        if ($success === false) {
            throw new AuthenticationException('Stage 1B: Authentication failure');
        }

        $cookieHeader = $response->header('Set-Cookie');

        $this->sessionId = Str::betweenFirst($cookieHeader, 'passbolt_session=', ';');
    }

    /**
     * @throws RequestException
     */
    private function initKeyring(): void
    {
        $response = $this->client->get('/auth/verify.json');

        $response->throwUnlessStatus(Response::HTTP_OK);

        $this->serverKey['keydata'] = $response->json('body.keydata');
        $this->serverKey['info'] = $this->gpg->import($this->serverKey['keydata']);
        $this->privateKey['info'] = $this->gpg->import($this->privateKey['keydata']);
    }

    private function generateGpgAuthToken(): string
    {
        return 'gpgauthv1.3.0|36|' . Str::uuid() . '|gpgauthv1.3.0';
    }
}
