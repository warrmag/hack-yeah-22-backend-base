<?php

namespace App\Controller;

use App\DTO\RatingRequest;
use App\Entity\Trash;
use App\Enum\TrashType;
use App\Service\OpinionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/trash/{code}/opinions', name: 'create_opinion', methods: [Request::METHOD_POST])]
class AddOpinion
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly OpinionService $service,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function __invoke(string $code, Request $request): JsonResponse
    {
        $trash = $this->em->find(Trash::class, $code);

        if (!$trash instanceof Trash) {
            return new JsonResponse('Unable to locate resource', Response::HTTP_NOT_FOUND);
        }

        /** @var RatingRequest $rating */
        $rating = $this->serializer->deserialize($request->getContent(), RatingRequest::class, 'json');

        try {
            $this->service->addOpinion($trash, $rating);
        } catch (\ValueError $exception) {
            return new JsonResponse(['message' => 'Invalid trash type'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(
            'ok',
            Response::HTTP_CREATED
        );
    }
}
