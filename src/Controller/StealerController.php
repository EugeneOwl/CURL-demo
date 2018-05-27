<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\DataStealer;

class StealerController extends AbstractController
{
    /**
     * @Route("/stealer", name="app_stealer")
     */
    public function run(DataStealer $stealer): Response
    {
        $data = $stealer->getUsersFromCity($_POST["city"]);
        return $this->render("stealer.html.twig", [
            "title"       => "Stealer page",
            "header"      => "Welcome to stealer page, anonymous",
            "stolen_data" => $data,
        ]);
    }
}