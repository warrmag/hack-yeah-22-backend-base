<?php

namespace App\Controller;

use App\DTO\Code;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/code', name: 'code', methods: [Request::METHOD_POST])]
final class ScanCode
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $code = $this->serializer->deserialize($request->getContent(), Code::class, 'json');
        dd($code);
        $rr = $request->getContent();

        return new JsonResponse(['xyz']);
    }
}
