<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout (RequestStack $requestStack, AuthenticationUtils $authenticationUtils, TokenStorageInterface $tokenStorage): Response
    {
        $tokenStorage->setToken(null);
        $request = $requestStack->getCurrentRequest();
        $request->getSession()->invalidate();
        return $this->redirectToRoute('login');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    

        //return $this->render('security/logout.html.twig');
    }
}
