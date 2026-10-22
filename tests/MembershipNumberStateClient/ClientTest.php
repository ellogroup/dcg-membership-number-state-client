<?php

use Dcg\Client\MembershipNumberState\Client;

use Dcg\Client\MembershipNumberState\Utils\API;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Dcg\Client\MembershipNumberState\Config;

class ClientTest extends TestCase
{
    protected $prodConfig = null;
    protected $testConfig = null;


    public function setUp(): void
    {
        parent::setUp();

        $this->prodConfig = Config::getInstance(__DIR__.'/../../config.php');
        $this->testConfig = Config::getInstance(__DIR__.'/../../config.php', \Dcg\Config::ENV_TEST);
    }

    public function testClientActivateCallSingleIsOk()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['OK']))
        ]);
        $handler = HandlerStack::create($mock);

        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        Api::setClient($client);

        $client = new Client($this->testConfig);
        $data = [['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-01 23:59:59']];
        $this->assertTrue($client->activate($data));
    }

    public function testClientActivateCallManyIsOk()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['OK']))
        ]);
        $handler = HandlerStack::create($mock);

        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        Api::setClient($client);

        $client = new Client($this->testConfig);
        $data = [
            ['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-01 23:59:59'],
            ['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-02 23:59:59'],
        ];
        $this->assertTrue($client->activate($data));
    }

    public function testClientActivateCallIsNotOk()
    {
        $mock = new MockHandler([
            new Response(404, [], json_encode(['Error']))
        ]);
        $handler = HandlerStack::create($mock);

        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        Api::setClient($client);

        $client = new Client($this->testConfig);
        $data = [
            ['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-01 23:59:59'],
            ['membershipNumber'=>'1234abc1234','expiryDate'=>'2018-01-02 23:59:59'],
        ];
        $this->assertFalse($client->activate($data));
    }

    public function testClientUsesDefaultHeader()
    {
        $client = new Client($this->testConfig);
        $headers = $client->getHeaders();
        $this->assertArrayHasKey('Access-Token', $headers);
        $this->assertEquals($headers['Access-Token'], 'TEST_TOKEN');
    }

    public function testClientUsesSetHeader()
    {
        $client = new Client($this->testConfig);
        $client->setHeaders(['Access-Token' => 'test']);
        $headers = $client->getHeaders();
        $this->assertEquals($headers['Access-Token'], 'test');
    }


    public function testTestConfig() {
        $client = new Client($this->testConfig);
        $headers = $client->getHeaders();
        $this->assertEquals($headers['Access-Token'], 'TEST_TOKEN');
    }

    public function testProdConfig() {
        $client = new Client($this->prodConfig);
        $headers = $client->getHeaders();
        $this->assertEquals($headers['Access-Token'], 'PROD_TOKEN');
    }

}