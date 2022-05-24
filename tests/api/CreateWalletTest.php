<?php

declare(strict_types=1);

namespace App\Tests\api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateWalletTest extends WebTestCase
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
            "personalIdNumber" => "00000",
            "currency" => "EUR"
        ];

        self::$client->jsonRequest('POST', 'api/wallet', $parameters);
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_CREATED, self::$client->getResponse()->getStatusCode());
        $this->assertEquals('The wallet has been successfully created.', $contentArray['message']);
        $this->assertFalse($contentArray['error']);
    }

    public function testBadRequestParameters(): void
    {
        $parameters = [
            "personalIdNumber" => "",
            "currency" => "EU"
        ];

        $errorExcepted = [
            "personalIdNumber" => [
                0 => "Personal id number is required. It should have 1 characters or more."
            ],
            "currency" => [
                0 => "The value you selected is not a valid choice."
            ]
        ];

        self::$client->jsonRequest('POST', 'api/wallet', $parameters);
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, self::$client->getResponse()->getStatusCode());
        $this->assertEquals($errorExcepted, $contentArray['message']);
        $this->assertTrue($contentArray['error']);
    }

    public function testBankAccountDoesntExists(): void
    {
        $parameters = [
            "personalIdNumber" => "123",
            "currency" => "EUR"
        ];

        self::$client->jsonRequest('POST', 'api/wallet', $parameters);
        $contentArray = json_decode(self::$client->getResponse()->getContent(), true);

        $this->assertEquals(Response::HTTP_NOT_FOUND, self::$client->getResponse()->getStatusCode());
        $this->assertEquals("Account not found.", $contentArray['message']);
        $this->assertTrue($contentArray['error']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        self::$client->restart();
    }
}
