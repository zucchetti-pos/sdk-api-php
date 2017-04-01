<?php

namespace Compufacil\Service;

class Configuration
{
    private $baseUrl;
    private $version;

    public function __construct(array $data = [])
    {
        $this->baseUrl = isset($data['baseUrl']) ? $data['baseUrl'] : 'http://homolog.compufacil.com.br' ;
        $this->version = isset($data['version']) ? $data['version'] : '1' ;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function toArray()
    {
        return [
            'baseUrl' => $this->baseUrl,
            'version' => $this->version
        ];
    }
}
