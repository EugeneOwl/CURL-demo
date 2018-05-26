<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; // для аннотации роутинга и привязи страницы к URL как следствие
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // контроллер теперь имеет метод render для вывода вьюх
use Symfony\Component\HttpFoundation\Response; // для строгой типизации (контроллер возвращает объект этого класса)

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_homepage")
     */
    public function run(): Response
    {
        $username = $this->getUser()->getUsername();
        return $this->render("home.html.twig", [
            "title"     => "Welcome",
            "header"    => "Welcome, $username!",
            "subHeader" => "from {$this->getUser()->getCity()->getName()}",
        ]);
    }
}