<?php

namespace Lumin\Integrations\Classes\Services;

use ApplicationException;
use Http;

abstract class IntegrationService
{
    private string $requestUrl;

    public array $entries;

    /**
     * @throws ApplicationException
     */
    public function __construct(string $requestUrl= null)
    {
        $this->requestUrl = $requestUrl;

        if (empty($this->requestUrl)) {
            throw new ApplicationException('API URL is not specified');
        }

        $this->entries = $this->get($this->requestUrl);
    }

    /**
     * @param string $url
     * @return array
     */
    private function get(string $url) : array
    {
        $articlesResponse = Http::get($url);
        return $articlesResponse->json();
    }

}
