<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    #[Route(path: 'health-check', name: 'health_check', methods: 'GET')]
    public function healthCheck(): JsonResponse
    {
        return new JsonResponse(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
    }

    public function jsonMessage($message = '', $status = Response::HTTP_CREATED): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(['message' => $message, 'error' => false], 'json'),
            $status,
            [],
            true
        );
    }

    public function jsonError($message = '', $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(['message' => $message, 'error' => true], 'json'),
            $status,
            [],
            true
        );
    }
}
