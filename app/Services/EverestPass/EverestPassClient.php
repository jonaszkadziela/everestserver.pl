<?php

namespace App\Services\EverestPass;

use App\Services\EverestPass\Exceptions\AuthenticationException;
use App\Services\EverestPass\Traits\AuthenticatesUsers;
use gnupg as GnuPG;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class EverestPassClient
{
    use AuthenticatesUsers;

    private PendingRequest $client;

    /**
     * @throws AuthenticationException
     * @throws RequestException
     * @throws RuntimeException
     */
    public function __construct(public readonly string $baseUrl, string $privateKeyPath = null, string $privateKeyPassphrase = null)
    {
        if (extension_loaded('gnupg') === false) {
            throw new RuntimeException('The "gnupg" extension is required to use this service');
        }

        $this->client = Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->withResponseMiddleware(fn (ResponseInterface $response) => $this->extractCsrfTokenMiddleware($response));

        $this->gpg = new GnuPG();
        $this->gpg->setErrorMode(GnuPG::ERROR_EXCEPTION);
        $this->csrfToken = null;
        $this->sessionId = null;

        $this->privateKey['keydata'] = file_get_contents($privateKeyPath ?? config('services.everestpass.private_key.path'));
        $this->privateKey['passphrase'] = $privateKeyPassphrase ?? config('services.everestpass.private_key.passphrase');

        $this->authenticate();

        $this->client->withHeaders([
            'Cookie' => $this->getCookies(true),
            'X-CSRF-Token' => $this->csrfToken,
        ]);
    }

    /**
     * Get all users, optionally filtered by the search term.
     *
     * @param string|null $search - Does a wildcard text search. Will find users where either of the Username, First Name or Last Name contains the search term
     * @throws RequestException
     */
    public function getUsers(string $search = null): array
    {
        return $this->client
            ->get('/users.json', $search === null ? [] : [
                'filter[search]' => $search,
            ])
            ->throwUnlessStatus(Response::HTTP_OK)
            ->json();
    }

    /**
     * Creates a new user.
     *
     * @throws RequestException
     */
    public function createUser(string $email, string $firstName, string $lastName): array
    {
        return $this->client
            ->post('/users.json', [
                'username' => $email,
                'profile' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ],
            ])
            ->throwUnlessStatus(Response::HTTP_OK)
            ->json();
    }
}
