<?php

declare(strict_types = 1);

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="app_registration")
     */
    public function run(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $isUsernameFree = true;
        $form->handleRequest($request);
        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            $isUsernameFree = $this->getDoctrine()->getRepository(User::class)->isUsernameFree($user->getUsername())
            ) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render("registration.html.twig", [
            "title"             => "log up",
            "header"            => "Registration",
            "form"              => $form->createView(),
            "username_message"  => $isUsernameFree ? "" : "Username is already taken",
        ]);
    }
}