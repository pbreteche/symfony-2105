<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("")
     */
    public function index(
        AuthorRepository $authorRepository
    ): Response {
        return $this->render('user/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"})
     */
    public function edit(
        Author $author,
        Request $request
    ): Response {
        $form = $this->createForm(AuthorType::class, $author);

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}