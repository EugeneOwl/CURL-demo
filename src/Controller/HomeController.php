<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\City;
use App\Form\CityType;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_homepage")
     */
    public function run(Request $request): Response
    {
        $username = $this->getUser()->getUsername();
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);
        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            ($cityIndexNumber = $this->getDoctrine()->getRepository(City::class)->getCityIndexNumber($city->getName())) !== -1
        ) {
            $city = $this->getDoctrine()->getRepository(City::class)->findOneBy(["index_number" => $cityIndexNumber]);
        }
        return $this->render("home.html.twig", [
            "title"          => "Welcome",
            "header"         => "Welcome, $username!",
            "subHeader"      => "from {$this->getUser()->getCity()->getName()}",
            "searchForm"     => $form->createView(),
            "city_message"   => !isset($cityIndexNumber) || $cityIndexNumber !== -1 ? "" : "No such city found.",
            "cityUsers"      => $city->getUsers(),
        ]);
    }
}