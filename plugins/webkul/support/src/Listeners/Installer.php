<?php

declare(strict_types=1);

namespace Webkul\Support\Listeners;

use Exception;
use GuzzleHttp\Client;
use Webkul\Security\Models\User;

final class Installer
{
    /**
     * Api endpoint
     */
    private const string API_ENDPOINT = 'https://updates.aureuserp.com/api/updates';

    /**
     * After Krayin is successfully installed
     */
    public function installed(): void
    {
        $user = User::first();

        $httpClient = new Client;

        try {
            $httpClient->request('POST', self::API_ENDPOINT, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'domain' => config('app.url'),
                    'email' => $user?->email,
                    'name' => $user?->name,
                ],
            ]);
        } catch (Exception) {
            /**
             * Skip the error
             */
        }
    }
}
