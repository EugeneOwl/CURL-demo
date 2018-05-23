<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function run(): Response
    {
        return $this->render("login.html.twig", [
            "title" => "log in",
            "header" => "Log in",
        ]);
    }
}