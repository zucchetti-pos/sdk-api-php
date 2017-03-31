<?php

namespace CompufacilTest\Service;

use Compufacil\Service\Compufacil;

class CompufacilTest extends \PHPUnit\Framework\TestCase
{
    public function testSignUp_returnId_whenPassedData()
    {
        $service = new Compufacil();
        $result = $service->signIn('test@jhonmike.com.br', '123456');
        static::assertTrue(isset($result['access_token']));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid token
     */
    public function testRpcService_returnExceptionToken_whenGetClientWithoutToken()
    {
        $service = new Compufacil();
        $result = $service->rpcService('person.get-person.json', [
            'isClient' => true,
            'maxResults' => 10,
            'page' => 1
        ]);
    }

    public function testRpcService_returnDataListClients_whenGetClientWithoutToken()
    {
        $service = new Compufacil();
        $dataLogin = $service->signIn('test@jhonmike.com.br', '123456');
        $service->setToken($dataLogin['access_token']);

        $result = $service->rpcService('person.get-person.json', [
            'isClient' => true,
            'maxResults' => 10,
            'page' => 1
        ]);

        static::assertTrue(isset($result['data']));
    }
}
