<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="app_registration")
     */
    public function run(): Response
    {
        return $this->render("registration.html.twig", [
            "title" => "log up",
            "header" => "Registration",
        ]);
    }
}