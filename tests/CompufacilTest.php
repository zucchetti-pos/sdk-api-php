<?php

namespace CompufacilTest;

use Compufacil\Compufacil;
use Compufacil\Configuration;

class CompufacilTest extends \PHPUnit\Framework\TestCase
{
    public function testSignIn_returnId_whenPassedData()
    {
        $config = [
            'environment' => 'homolog',
            'version' => '1'
        ];

        $service = new Compufacil($config);
        $result = $service->signIn('test@sdk.com', '123456');

        static::assertTrue(isset($result['access_token']));
    }

    /**
     * @expectedException \Compufacil\CompufacilException
     * @expectedExceptionMessage Invalid token
     */
    public function testRpcService_returnExceptionToken_whenGetClientWithoutToken()
    {
        $config = [
            'environment' => 'homolog',
            'version' => '1'
        ];

        $service = new Compufacil($config);
        $service->rpcService('person.get-person', [
            'isClient' => true,
            'maxResults' => 10,
            'page' => 1
        ]);
    }

    public function testRpcService_returnDataListClients_whenGetClientWithoutToken()
    {
        $config = [
            'environment' => 'homolog',
            'version' => '1'
        ];

        $service = new Compufacil($config);
        $dataLogin = $service->signIn('test@sdk.com', '123456');
        $service->setToken($dataLogin['access_token']);

        $result = $service->rpcService('person.get-person', [
            'isClient' => true,
            'maxResults' => 10,
            'page' => 1
        ]);

        static::assertTrue(isset($result['data']));
    }
}
