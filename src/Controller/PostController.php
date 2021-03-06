<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\AuthorRepository;
use App\Repository\PostRepository;
use App\Service\PostSearcherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/admin", methods="GET")
 * @IsGranted("ROLE_ADMIN")
 */
class PostController extends AbstractController
{
    /**
     * @Route("")
     */
    public function index(PostRepository $postRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (false) {
            throw $this->createAccessDeniedException('');
        }

        $posts = $postRepository->findLatest2();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager,
        AuthorRepository $authorRepository,
        TranslatorInterface $translator
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'validation_groups' => ['published'],
        ]);

        $form->handleRequest($request);
        $user = $this->getUser();
        $author = $authorRepository->findOneBy(['user' => $user]);
        $post->setWrittenBy($author);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', $translator->trans('post.create.success'));

            return $this->redirectToRoute('app_author_show', [
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
     * @IsGranted("POST_EDIT", subject="post")
     */
    public function edit(
        Post $post,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(PostType::class, $post, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'Votre article a bien ??t?? modifi??.');

            return $this->redirectToRoute('app_author_show', [
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

            $this->addFlash('success', 'Votre article a bien ??t?? supprim??.');

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/delete.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/search")
     */
    public function search(
        Request $request,
        PostSearcherInterface $postSearcher
    ): Response {
        $keywordName = $request->query->get('q');

        return $this->render('post/search.html.twig', [
            'keyword_name' => $keywordName,
            'posts' => $postSearcher->search($keywordName),
        ]);
    }
}
