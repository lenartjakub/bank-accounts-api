<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dictionary\ApiMessageResponse;
use App\DTO\DepositDTO;
use App\DTO\WalletDTO;
use App\DTO\WithdrawDTO;
use App\Enum\WalletEventType;
use App\Exception\BadParamRequestException;
use App\Exception\BankAccountNotFoundException;
use App\Exception\NoSufficientFundsException;
use App\Exception\UnsupportedFileTypeException;
use App\Exception\WalletNotFoundException;
use App\Service\History\WalletHistoryServiceInterface;
use App\Service\Wallet\Create\CreateWalletServiceInterface;
use App\Service\Wallet\Show\ShowWalletServiceInterface;
use App\Service\Wallet\WalletOperationServiceFactory;
use DateTime;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class WalletController extends ApiController
{
    private ShowWalletServiceInterface $showWalletService;
    private CreateWalletServiceInterface $createWalletService;
    private WalletOperationServiceFactory $operationServiceFactory;
    private WalletHistoryServiceInterface $walletHistoryService;

    public function __construct(
        ShowWalletServiceInterface    $showWalletService,
        CreateWalletServiceInterface  $createWalletService,
        SerializerInterface           $serializer,
        WalletOperationServiceFactory $operationServiceFactory,
        WalletHistoryServiceInterface $walletHistoryService
    )
    {
        parent::__construct($serializer);

        $this->showWalletService = $showWalletService;
        $this->createWalletService = $createWalletService;
        $this->operationServiceFactory = $operationServiceFactory;
        $this->walletHistoryService = $walletHistoryService;
    }

    #[Route(path: 'wallet/{iban}', name: 'show_wallet', methods: Request::METHOD_GET)]
    public function show(string $iban): JsonResponse
    {
        try {
            $wallet = $this->showWalletService->show($iban);

            return $this->jsonMessage($wallet, Response::HTTP_OK);
        } catch (WalletNotFoundException $exception) {
            return $this->jsonError($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    #[Route(path: 'wallet', name: 'create_wallet', methods: Request::METHOD_POST)]
    #[ParamConverter('walletDTO', converter: "fos_rest.request_body")]
    public function create(WalletDTO $walletDTO): JsonResponse
    {
        try {
            $this->createWalletService->handle($walletDTO);

            return $this->jsonMessage(ApiMessageResponse::WALLET_CREATED);
        } catch (BadParamRequestException $exception) {
            return $this->jsonError($exception->getMessages());
        } catch (BankAccountNotFoundException $exception) {
            return $this->jsonError($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {
            return $this->jsonError($exception->getMessage());
        }
    }

    #[Route(path: 'wallet/deposit', name: 'deposit_wallet', methods: Request::METHOD_POST)]
    #[ParamConverter('depositDTO', converter: "fos_rest.request_body")]
    public function deposit(DepositDTO $depositDTO): JsonResponse
    {
        try {
            $this->operationServiceFactory
                ->make(WalletEventType::DEPOSIT)
                ->handle($depositDTO);

            return $this->jsonMessage(ApiMessageResponse::AMOUNT_DEPOSITED);
        } catch (BadParamRequestException $exception) {
            return $this->jsonError($exception->getMessages());
        } catch (WalletNotFoundException $exception) {
            return $this->jsonError($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            return $this->jsonError($exception->getMessage());
        }
    }

    #[Route(path: 'wallet/withdraw', name: 'withdraw_wallet', methods: Request::METHOD_POST)]
    #[ParamConverter('withdrawDTO', converter: "fos_rest.request_body")]
    public function withdraw(WithdrawDTO $withdrawDTO): JsonResponse
    {
        try {
            $this->operationServiceFactory
                ->make(WalletEventType::WITHDRAW)
                ->handle($withdrawDTO);

            return $this->jsonMessage(ApiMessageResponse::AMOUNT_WITHDRAWN);
        } catch (BadParamRequestException $exception) {
            return $this->jsonError($exception->getMessages());
        } catch (WalletNotFoundException|NoSufficientFundsException $exception) {
            return $this->jsonError($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            return $this->jsonError($exception->getMessage());
        }
    }

    #[Route(path: 'wallet/{iban}/history/{fileType}', name: 'history_wallet', methods: Request::METHOD_GET)]
    public function generateHistory(string $iban, string $fileType): Response|JsonResponse
    {
        try {
            $file = $this->walletHistoryService->handle($iban, $fileType);

            return $this->getFileResponse($file, $fileType);
        } catch (BadParamRequestException $exception) {
            return $this->jsonError($exception->getMessages());
        } catch (WalletNotFoundException $exception) {
            return $this->jsonError($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (UnsupportedFileTypeException|Exception $exception) {
            return $this->jsonError($exception->getMessage());
        }
    }

    private function getFileResponse(string $file, string $fileType): Response
    {
        $date = (new DateTime('now'))->format("Y-m-d");
        $fileName = sprintf('wallet-history-%s.%s', $date, $fileType);

        $response = new Response($file);
        $response->headers->set('Content-Encoding', 'UTF-8');
        $response->headers->set('Content-Type', 'text/' . $fileType . '; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $fileName);

        return $response;
    }
}
