<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\WeatherForecast;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function run(WeatherForecast $weatherForecast): Response
    {
        return $this->render("main.html.twig", [
            "title" => "main page",
            "header" => "Main page",
            "weatherShortDescription" => $weatherForecast->getWeather($_POST["city"]),
        ]);
    }
}