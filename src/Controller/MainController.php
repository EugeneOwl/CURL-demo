<?php

declare(strict_types = 1);

namespace App\Controller;


use App\Form\CityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\WeatherForecast;
use App\Entity\City;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function run(WeatherForecast $weatherForecast): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        return $this->render("main.html.twig", [
            "title" => "main page",
            "header" => "Main page",
            "search" => $form->createView(),
            "weatherShortDescription" => $weatherForecast->getWeather($_POST["city"]),
        ]);
    }
}