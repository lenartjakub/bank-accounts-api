<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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
