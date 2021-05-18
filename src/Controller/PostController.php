<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", methods="GET")
 */
class PostController extends AbstractController
{
    /**
     * @Route("")
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findLatest();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route(
     *     "/{id}",
     *     requirements={"id":"\d+"}
     * )
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $post = new Post();
        $form = $this->createFormBuilder($post)
            ->add('title')
            ->add('body')
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTime());

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Votre article a bien été enregistré.');

            return $this->redirectToRoute('app_post_show', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "\d+"},
     *     methods={"GET", "PUT"}
     * )
     */
    public function edit(
        Post $post,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createFormBuilder($post, [
            'method' => 'PUT'
        ])
            ->add('title')
            ->add('body')
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'Votre article a bien été modifié.');

            return $this->redirectToRoute('app_post_show', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "\d+"},
     *     methods={"GET", "DELETE"}
     * )
     */
    public function delete(
        Post $post,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        if ('DELETE' === $request->getMethod()) {
            $manager->remove($post);
            $manager->flush();

            $this->addFlash('success', 'Votre article a bien été supprimé.');

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/delete.html.twig', [
            'post' => $post,
        ]);
    }
}
