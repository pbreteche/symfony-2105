<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index(): Response
    {

        return new Response('Bonjour à tous!</body>');
    }

    /**
     * @Route(
     *     "/hello/{name}",
     *     defaults={"name": "Dawan"},
     *     requirements={"name": "[A-Z][a-z]*"},
     *     methods={"GET", "POST", "PUT"}
     * )
     */
    public function hello(string $name): Response
    {
        return $this->render('default/hello.html.twig');
    }
}
