<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'modification OK');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
