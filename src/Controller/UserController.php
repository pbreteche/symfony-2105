<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\User;
use App\Form\AuthorType;
use App\Form\PasswordType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $passwordForm = $this->createForm(PasswordType::class, [], [
            'action' => $this->generateUrl('app_user_changepassword', [
                'id' => $author->getUser()->getId(),
            ]),
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'modification OK');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'password_form' => $passwordForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/password", methods="PUT")
     */
    public function changePassword(
        User $user,
        Request $request,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $manager,
        AuthorRepository $authorRepository
    ): Response {
        $passwordForm = $this->createForm(PasswordType::class, [], [
            'action' => $this->generateUrl('app_user_changepassword', [
                'id' => $user->getId(),
            ]),
            'method' => 'PUT',
        ]);

        $passwordForm->handleRequest($request);

        if ($passwordForm->isValid()) {
            $newPassword = $passwordForm->get('password')->getData();
            $user->setPassword($encoder->encodePassword($user, $newPassword));
            $manager->flush();

            $this->addFlash('success', 'mot de passe changé');
        } else {
            foreach ($passwordForm->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirectToRoute('app_user_edit', [
            'id' => $authorRepository->findOneBy(['user' => $user])->getId(),
        ]);
    }
}
