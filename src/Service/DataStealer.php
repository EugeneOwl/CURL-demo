<?php

declare(strict_types = 1);

namespace App\Service;


class DataStealer
{
    private const BOT_FAILED_REGISTRATION_MESSAGE = "Not successful bot registration. Try again.";
    private const BOT_FAILED_LOGIN_MESSAGE = "Not successful bot login. Try again.";

    private const TARGET_REGISTRATION_PAGE = "http://curl-demo.loc/registration";
    private const TARGET_LOGIN_PAGE = "http://curl-demo.loc/login";
    private const TARGET_DATA_PAGE = "http://curl-demo.loc/home";
    private const COOKIE_FILE = "cookie,txt";

    private const CSRF_INPUT_TAG_FRAGMENT_REGISTRATION = "<input type=\"hidden\" id=\"user__token\"";
    private const CSRF_INPUT_TAG_FRAGMENT_LOGIN = "<input type=\"hidden\" id=\"city__token\"";
    private const USERLIST_FRAGMENT = "<div id=\"userList\">";
    private const CSRF_INPUT_TAG_LENGTH = 109;
    private const CSRF_PREFIX_PHRASE = "value=";
    private const DEFAULT_PASSWORD = "password";

    private $botUsername;
    private $botPassword;
    private $postValuesRegistration;
    private $postValuesLogin;
    private $postValuesSearch;

    private function getCSRFToken(string $contents, string $inputTagFragment): string
    {
        $CSRFInputTagStartPosition =  stripos($contents, $inputTagFragment);
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

    private function setPostParametersRegistration(string $CSRFToken): void
    {
        $this->postValuesRegistration["user[username]"] = $this->botUsername;
        $this->postValuesRegistration["user[plainPassword][first]"] = $this->botPassword;
        $this->postValuesRegistration["user[plainPassword][second]"] = $this->botPassword;
        $this->postValuesRegistration["user[plainCityName]"] = "Минск";
        $this->postValuesRegistration["user[_token]"] = $CSRFToken;
    }

    private function registrateBot(): int
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::TARGET_REGISTRATION_PAGE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, self::COOKIE_FILE);
        $contents = curl_exec($ch);

        $this->setBotParameters();
        $CSRF = $this->getCSRFToken($contents, self::CSRF_INPUT_TAG_FRAGMENT_REGISTRATION);
        $this->setPostParametersRegistration($CSRF);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postValuesRegistration);

        curl_exec($ch);
        $errorCode = curl_errno($ch);

        curl_close($ch);
        return $errorCode;
    }

    private function setPostParametersLogin(): void
    {
        $this->postValuesLogin["_username"] = "Eugene";
        $this->postValuesLogin["_password"] = "password";
    }

    private function login()
    {
        $this->setPostParametersLogin();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::TARGET_LOGIN_PAGE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->postValuesLogin));
        curl_setopt($ch, CURLOPT_COOKIEJAR, self::COOKIE_FILE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_exec($ch);
        return $ch;
    }

    private function setPostParametersSearch(string $cityName, string $CSRF): void
    {
        $this->postValuesSearch["city[name]"] = $cityName;
        $this->postValuesSearch["city[_token]"] = $CSRF;
    }

    private function cutOfUserList(string $data): string
    {
        $userListStartPos = stripos($data, self::USERLIST_FRAGMENT);
        return substr($data, $userListStartPos);
    }

    private function stealData($ch, string $cityName): string
    {
        curl_setopt($ch, CURLOPT_URL, self::TARGET_DATA_PAGE);
        $contents = curl_exec($ch);
        $CSRF = $this->getCSRFToken($contents, self::CSRF_INPUT_TAG_FRAGMENT_LOGIN);
        curl_setopt($ch, CURLOPT_POST, true);
        $this->setPostParametersSearch($cityName, $CSRF);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postValuesSearch);
        $data = curl_exec($ch);
        curl_close($ch);
        $userList  = $this->cutOfUserList($data);
        return $userList;
    }

    public function getUsersFromCity(?string $cityName): string
    {
        if (empty($cityName)) {
            return "";
        }
        if (($registrationError = $this->registrateBot()) !== 0) {
            return self::BOT_FAILED_REGISTRATION_MESSAGE . "($registrationError)";
        }
        $ch = $this->login();
        if (curl_errno($ch) !== 0) {
            return self::BOT_FAILED_LOGIN_MESSAGE . "(" . curl_error($ch) . ")";
        }
        $stolenData = $this->stealData($ch, $cityName);
        return $stolenData;
    }
}