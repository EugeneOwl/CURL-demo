<?php

declare(strict_types = 1);

namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\City;

class WeatherForecast extends AbstractController
{
    private const CITY_NOT_FOUND_MESSAGE = "City not found :(";

    private $meteoWebSiteURL = "http://6.pogoda.by/";
    private $encodingFrom = "Windows-1251";
    private $encodingTo = "UTF-8";
    private $unneededPart = "<img title='Данные аэропорта' src='6/template/img/14-plane.gif' width='14' height='15'>";
    private $startPhrase = "Сейчас <strong>в";
    private $finishPhrase = "<!-- Температура моря если есть -->";

    private function getCityIndex(?string $inputCity): int
    {
        if (empty($inputCity)) {
            return -1;
        }
        $cityName = mb_strtolower(trim($inputCity));
        return $this->getDoctrine()->getRepository(City::class)->getCityIndexNumber($cityName);
    }

    private function getPageContents(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        curl_close($ch);
        $encodedContents = iconv(
            $this->encodingFrom,
            $this->encodingTo,
            $contents
        );
        return $encodedContents;
    }

    private function getWeatherDescription(int $cityIndex): string
    {
        $weatherForecast = $this->getPageContents($this->meteoWebSiteURL . $cityIndex);
        $weatherForecast = str_replace($this->unneededPart, "", $weatherForecast);
        $startPos = stripos($weatherForecast, $this->startPhrase);
        $finishPos = stripos($weatherForecast, $this->finishPhrase);
        $shortDescription = substr($weatherForecast, $startPos, $finishPos - $startPos);
        return $shortDescription;
    }

    public function getWeather(?string $city): string
    {
        $cityIndex = $this->getCityIndex($city);
        if ($cityIndex === -1) {
            return self::CITY_NOT_FOUND_MESSAGE;
        }
        return $this->getWeatherDescription($cityIndex);
    }
}