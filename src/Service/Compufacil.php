<?php

namespace Compufacil\Service;

class Compufacil
{
    private $token;
    private $config;
    private $defaultConfig = [
        'url' => 'http://homolog.compufacil.com.br',
        'version' => '1'
    ];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function signIn(string $email, string $password)
    {
        $config = $this->getConfig();
        $params = [
            'url' => $config['url'].'/rpc/v'.$config['version'].'/application.authenticate.json',
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

        $config = $this->getConfig();
        $data = [
            'url' => $config['url'].'/rpc/v'.$config['version'].'/'.$serviceName,
            'content' => $params
        ];

        return $this->streamService($data);
    }

    public function streamService(array $data) : array
    {
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded",
                'content' => http_build_query($data['content']),
            ],
        ];

        if ($this->token) {
            $opts['http']['header'] .= "\nAuthorization-Compufacil: ".$this->token;
        }

        $context = stream_context_create($opts);
        $result = file_get_contents($data['url'], false, $context);

        return json_decode($result, true);
    }

    public function setConfig($config) : Compufacil
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig() : array
    {
        return array_merge_recursive($this->defaultConfig, $this->config);
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
