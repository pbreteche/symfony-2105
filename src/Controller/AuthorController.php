<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", methods="GET")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("")
     */
    public function index(
        AuthorRepository $authorRepository
    ): Response {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function posts(
        Author $author,
        PostRepository $postRepository
    ): Response {
        return $this->render('author/posts.html.twig', [
            'author' => $author,
            'posts' => $postRepository->findBy(['writtenBy' => $author], ['createdAt' => 'DESC']),
        ]);
    }
}