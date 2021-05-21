<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", defaults={"_format": "json"})
 */
class AjaxController extends AbstractController
{

    /**
     * @Route("/posts", methods="GET")
     */
    public function index(
        PostRepository $postRepository
    ): Response {
        $posts = $postRepository->findAll();

        $serializedPosts = [];

        foreach ($posts as $post) {
            $serializedPosts[] = [
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
                'author' => $post->getWrittenBy()->getName(),
            ];
        }

        return $this->json($serializedPosts);
    }
}