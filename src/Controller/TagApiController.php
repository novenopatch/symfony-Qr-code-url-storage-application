<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TagApiController extends AbstractController
{
    #[Route('/api/tags', name: 'api_tag')]
    public function index(Request $request,TagRepository $tagRepository): JsonResponse
    {
        return $this->json($tagRepository->searchTags($request->query->get('q')), Response::HTTP_OK);
    }
}
