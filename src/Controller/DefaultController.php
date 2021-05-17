<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{

    public function index(): Response
    {

        return new Response('Bonjour à tous!</body>');
    }


    public function hello(string $name): Response
    {
        return new Response('Bonjour '.$name);
    }
}
