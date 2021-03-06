<?php

declare(strict_types = 1);

namespace App\Controller;


use App\Form\CityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\WeatherForecast;
use App\Entity\City;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function run(Request $request, WeatherForecast $weatherForecast): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $weatherShortDescription = "";
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $weatherShortDescription = $weatherForecast->getWeather($city->getName());
        }
        return $this->render("main.html.twig", [
            "title" => "main page",
            "header" => "Main page",
            "searchForm" => $form->createView(),
            "weatherShortDescription" => $weatherShortDescription,
        ]);
    }
}