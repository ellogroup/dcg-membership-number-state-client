<?php

use Dcg\Client\MembershipNumberState\Client;

use Dcg\Client\MembershipNumberState\Utils\API;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;
use PHPUnit\Framework\TestCase;
use Dcg\Client\MembershipNumberState\Config;

class ClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        Config::getInstance(__DIR__.'/../../config.php');
    }

    public function testClientActivateCallSingleIsOk()
    {
        $mock = new Mock([
            new Response(200, [], Stream::factory(json_encode(['OK'])))
        ]);

        $client = new \GuzzleHttp\Client();

        $client->getEmitter()->attach($mock);

        Api::setClient($client);

        $client = new Client();
        $data = [['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-01 23:59:59']];
        $this->assertTrue($client->activate($data));
    }

    public function testClientActivateCallManyIsOk()
    {
        $mock = new Mock([
            new Response(200, [], Stream::factory(json_encode(['OK'])))
        ]);

        $client = new \GuzzleHttp\Client();

        $client->getEmitter()->attach($mock);

        Api::setClient($client);

        $client = new Client();
        $data = [
            ['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-01 23:59:59'],
            ['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-02 23:59:59'],
        ];
        $this->assertTrue($client->activate($data));
    }

    public function testClientActivateCallIsNotOk()
    {
        $mock = new Mock([
            new Response(404, [], Stream::factory(json_encode(['Error'])))
        ]);

        $client = new \GuzzleHttp\Client();

        $client->getEmitter()->attach($mock);

        Api::setClient($client);

        $client = new Client();
        $data = [
            ['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-01 23:59:59'],
            ['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-02 23:59:59'],
        ];
        $this->assertFalse($client->activate($data));
    }

    public function testClientUsesDefaultHeader()
    {
        $client = new Client();
        $headers = $client->getHeaders();
        $this->assertArrayHasKey('Access-Token', $headers);
        $this->assertEquals($headers['Access-Token'], 'TEST_TOKEN');
    }

    public function testClientUsesSetHeader()
    {
        $client = new Client();
        $client->setHeaders(['Access-Token' => 'test']);
        $headers = $client->getHeaders();
        $this->assertEquals($headers['Access-Token'], 'test');
    }



}