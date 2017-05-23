<?php

namespace Compufacil;

class Configuration
{
    private $environment;
    private $version;

    public function __construct(array $data = [])
    {
        $this->environment = isset($data['environment']) ? $data['environment'] : Environment::HOMOLOG ;
        $this->version = isset($data['version']) ? $data['version'] : '1' ;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;
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
            'environment' => $this->environment,
            'version' => $this->version
        ];
    }
}
