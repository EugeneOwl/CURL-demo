<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TempController extends AbstractController
{
    /**
     * @Route("/temp", name="app_temp")
     */
    public function run(): Response
    {
        return $this->render("temp.html.twig", [
            "title" => "temp",
            "header" => "temp",
        ]);
    }
}