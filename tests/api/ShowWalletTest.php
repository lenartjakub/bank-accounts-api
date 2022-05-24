<?php

namespace App\Tests\api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ShowWalletTest extends WebTestCase
{
    private static ?KernelBrowser $client = null;

    public function setUp(): void
    {
        parent::setUp();
        if (null === self::$client) {
            self::$client = static::createClient();
        }
        self::$client->setServerParameter('CONTENT_TYPE', 'application/json');
    }

    public function testSuccess(): void
    {
        $message = [
            "iban" => "0001",
            "balance" => 0,
            "currency" => "EUR",
        ];

        self::$client->request('GET', 'api/wallet/0001');
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertEquals($message, $contentArray['message']);
        $this->assertFalse($contentArray['error']);
    }

    public function testWalletNotFound(): void
    {
        $message = "Wallet not found.";

        self::$client->request('GET', 'api/wallet/0003');
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, self::$client->getResponse()->getStatusCode());
        $this->assertEquals($message, $contentArray['message']);
        $this->assertTrue($contentArray['error']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        self::$client->restart();
    }
}
