<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('admin/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        } else {
            return $this->redirect('/admin');
        }
    }
}
