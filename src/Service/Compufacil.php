<?php

namespace Compufacil\Service;

class Compufacil
{
    private $token;
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function signIn(string $email, string $password)
    {
        $params = [
            'url' => $this->buildUrl('application.authenticate.json'),
            'content' => [
                'login' => $email,
                'password' => $password
            ]
        ];

        $result = $this->streamService($params);

        $this->setToken($result['access_token']);
        return $result;
    }

    public function rpcService(string $serviceName, array $params) : array
    {
        if (empty($this->token)) {
            throw CompufacilException::invalidToken();
        }

        $data = [
            'url' => $this->buildUrl($serviceName),
            'content' => $params
        ];

        return $this->streamService($data);
    }

    public function streamService(array $data) : array
    {
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data['content']),
            ],
        ];

        if ($this->token) {
            $opts['http']['header'] .= sprintf("\n Authorization-Compufacil: %s", $this->token);
        }

        $context = stream_context_create($opts);
        $result = file_get_contents($data['url'], false, $context);

        return json_decode($result, true);
    }

    public function buildUrl(string $serviceName) : string
    {
        return sprintf(
            "%s/rpc/v%s/%s",
            $this->configuration->getBaseUrl(),
            $this->configuration->getVersion(),
            $serviceName
        );
    }

    public function setToken($token) : Compufacil
    {
        $this->token = $token;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }
}
