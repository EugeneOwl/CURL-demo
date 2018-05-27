<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\DataStealer;
use App\Entity\City;
use App\Form\CityType;

class StealerController extends AbstractController
{
    /**
     * @Route("/stealer", name="app_stealer")
     */
    public function run(Request $request, DataStealer $stealer): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $stealer->getUsersFromCity($city->getName());
        }
        return $this->render("stealer.html.twig", [
            "title"       => "Stealer page",
            "header"      => "Welcome to stealer page, anonymous",
            "stolen_data" => $data ?? "",
            "searchForm"  => $form->createView(),
        ]);
    }
}