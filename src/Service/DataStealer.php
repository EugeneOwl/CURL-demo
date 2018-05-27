<?php

declare(strict_types = 1);

namespace App\Service;


class DataStealer
{
    private const BOT_FAILED_REGISTRATION_MESSAGE = "Not successful bot registration. Try again.";

    private const TARGET_REGISTRATION_PAGE = "http://curl-demo.loc/registration";
    private const COOKIE_FILE = "cookie,txt";

    private const CSRF_INPUT_TAG_FRAGMENT = <<< TOKEN
<input type="hidden" id="user__token"
TOKEN;
    private const CSRF_INPUT_TAG_LENGTH = 109;
    private const CSRF_PREFIX_PHRASE = "value=";
    private const DEFAULT_PASSWORD = "password";

    private $botUsername;
    private $botPassword;
    private $postValuesRegistration;

    private function getCSRFToken(string $contents): string
    {
        $CSRFInputTagStartPosition =  stripos($contents, self::CSRF_INPUT_TAG_FRAGMENT);
        $CSRFInputTag = (substr($contents, $CSRFInputTagStartPosition, self::CSRF_INPUT_TAG_LENGTH));
        $CSRFTokenStartPosition = stripos($CSRFInputTag, self::CSRF_PREFIX_PHRASE);
        $CSRFToken = substr($CSRFInputTag, $CSRFTokenStartPosition + strlen(self::CSRF_PREFIX_PHRASE));
        $CSRFToken = str_replace("\"", "", $CSRFToken);
        return $CSRFToken;
    }

    private function setBotParameters(): void
    {
        $this->botUsername = (string)mt_rand();
        $this->botPassword = self::DEFAULT_PASSWORD;
    }

    private function setPostParameters(string $CSRFToken): void
    {
        $this->postValuesRegistration["user[username]"] = $this->botUsername;
        $this->postValuesRegistration["user[plainPassword][first]"] = $this->botPassword;
        $this->postValuesRegistration["user[plainPassword][second]"] = $this->botPassword;
        $this->postValuesRegistration["user[plainCityName]"] = "Минск";
        $this->postValuesRegistration["user[_token]"] = $CSRFToken;
    }

    private function registrateBot(): bool
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::TARGET_REGISTRATION_PAGE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, self::COOKIE_FILE);
        $contents = curl_exec($ch);

        $this->setBotParameters();
        $this->setPostParameters($this->getCSRFToken($contents));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postValuesRegistration);

        curl_exec($ch);
        return (bool)curl_errno($ch);
    }

    public function getUsersFromCity(?string $cityName): string
    {
        if (!$this->registrateBot()) {
            return self::BOT_FAILED_REGISTRATION_MESSAGE;
        }
        #$ch = $this->login(); //sets ch
        #$stolenData = $this->stealData($ch, $cityName); //gets protected page> asks it for users and cut the list of
        return $this->botUsername . ' ' . $this->botPassword;
    }
}

$stealer = new DataStealer();
echo $stealer->getUsersFromCity("");