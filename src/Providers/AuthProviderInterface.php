<?php

namespace Anddye\JwtAuth\Providers;

/**
 * Interface AuthProviderInterface.
 */
interface AuthProviderInterface
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return mixed
     */
    public function byCredentials(string $username, string $password);

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function byId(int $id);
}
