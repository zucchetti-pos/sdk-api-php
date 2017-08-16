<?php

namespace Compufacil;

class Compufacil
{
    private $token;
    private $configuration;

    public function __construct(array $configuration = [])
    {
        $this->configuration = new Configuration($configuration);
    }

    public function signIn(string $email, string $password)
    {
        $result = $this->streamService(
            $this->buildEndpoint('application.authenticate'),
            'POST',
            ['login' => $email, 'password' => $password]
        );

        $this->setToken($result['access_token']);
        return $result;
    }

    public function rpcService(string $serviceName, array $params = [])
    {
        if (empty($this->token)) {
            throw CompufacilException::invalidToken();
        }

        return $this->streamService($this->buildEndpoint($serviceName), 'POST', $params);
    }

    public function streamService(string $endpoint, string $method = 'POST', array $params = [])
    {
        $opts = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Content-Type: application/x-www-form-urlencoded',
                ],
                'content' => http_build_query($params),
                'ignore_errors' => true,
            ],
        ];

        if ($this->token) {
            $opts['http']['header'][] .= sprintf("Authorization-Compufacil: %s", $this->token);
        }

        $context = stream_context_create($opts);
        $result = file_get_contents($endpoint, false, $context);

        return json_decode($result, true);
    }

    public function buildEndpoint(string $serviceName)
    {
        switch ($this->configuration->getEnvironment()) {
            case Environment::PRODUCTION:
                $baseUrl = 'https://app.compufacil.com.br';
                break;
            case Environment::HOMOLOG:
                $baseUrl = 'https://homolog.compufacil.com.br';
                break;
        }

        return sprintf(
            "%s/rpc/v%s/%s",
            $baseUrl,
            $this->configuration->getVersion(),
            $serviceName
        );
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }
}
