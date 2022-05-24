<?php

declare(strict_types=1);

namespace App\Tests\api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class WithdrawAmountTest extends WebTestCase
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
        $parameters = [
            "iban" => "0002",
            "amount" => 500
        ];

        self::$client->jsonRequest('POST', 'api/wallet/withdraw', $parameters);
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, self::$client->getResponse()->getStatusCode());
        $this->assertEquals("Amount has been withdraw from wallet.", $contentArray['message']);
        $this->assertFalse($contentArray['error']);
    }

    public function testBadRequestParams(): void
    {
        $parameters = [
            "iban" => "",
            "amount" => 10001
        ];

        $errorExcepted = [
            "iban" => [
                0 => "Iban is required. It should have 1 characters or more."
            ],
            "amount" => [
                0 => "Amount must be between 10 and 10 000."
            ]
        ];

        self::$client->jsonRequest('POST', 'api/wallet/withdraw', $parameters);
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, self::$client->getResponse()->getStatusCode());
        $this->assertEquals($errorExcepted, $contentArray['message']);
        $this->assertTrue($contentArray['error']);
    }

    public function testWalletNotFound(): void
    {
        $parameters = [
            "iban" => "777",
            "amount" => 777
        ];

        self::$client->jsonRequest('POST', 'api/wallet/withdraw', $parameters);
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, self::$client->getResponse()->getStatusCode());
        $this->assertEquals("Wallet not found.", $contentArray['message']);
        $this->assertTrue($contentArray['error']);
    }

    public function testNoSufficientFundsInWallet(): void
    {
        $parameters = [
            "iban" => "0001",
            "amount" => 777
        ];

        self::$client->jsonRequest('POST', 'api/wallet/withdraw', $parameters);
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, self::$client->getResponse()->getStatusCode());
        $this->assertEquals("You do not have sufficient funds in your wallet.", $contentArray['message']);
        $this->assertTrue($contentArray['error']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        self::$client->restart();
    }
}
