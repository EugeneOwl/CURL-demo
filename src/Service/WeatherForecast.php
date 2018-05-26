<?php

declare(strict_types = 1);

namespace App\Service;


class WeatherForecast
{
    private const CITY_NOT_FOUND_MESSAGE = "City not found :(";

    private $meteoWebSiteURL = "http://6.pogoda.by/";
    private $encodingFrom = "Windows-1251";
    private $encodingTo = "UTF-8";
    private $unneededPart = "<img title='Данные аэропорта' src='6/template/img/14-plane.gif' width='14' height='15'>";
    private $startPhrase = "<h2>Фактическая погода</h2>";
    private $finishPhrase = "<!-- Температура моря если есть -->";
    private $cities = [
        "гродно"  => "26825",
        "витебск" => "26666",
        "могилёв" => "26862",
        "гомель"  => "33041",
        "брест"   => "33008",
        "минск"   => "26850",
    ];

    private function getCityIndex(?string $inputCity): int
    {
        if (empty($inputCity)) {
            return -1;
        }
        $cityName = mb_strtolower(trim($inputCity));
        return $cities[$cityName] ?? -1;
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
        $weatherForecast = $this->getPageContents($this->meteoWebSiteURL);
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