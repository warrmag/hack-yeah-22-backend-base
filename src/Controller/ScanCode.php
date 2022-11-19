<?php

namespace App\Controller;

use App\DTO\Code;
use App\Exception\BarCodeLookupProductNotFoundException;
use App\Service\CodeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/code', name: 'code', methods: [Request::METHOD_POST])]
final class ScanCode
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly CodeService $codeService
    )
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $code = $this->serializer->deserialize($request->getContent(), Code::class, 'json');
            $trash = $this->codeService->obtainTrash($code);

            return new JsonResponse(
                data: $this->serializer->serialize($trash, 'json'),
                status: Response::HTTP_OK,
                json: true
            );
        }
        catch (BarCodeLookupProductNotFoundException $e) {
            return new JsonResponse(
                data: $this->serializer->serialize([
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                ], 'json'),
                status: Response::HTTP_NOT_FOUND,
                json: true
            );
        }
    }
}
