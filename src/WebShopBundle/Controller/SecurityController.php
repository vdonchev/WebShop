<?php

namespace WebShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Form\LoginForm;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     * @return Response
     */
    public function loginAction()
    {
        if ($this->get("security.authorization_checker")->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("homepage");
        }

        $authUtils = $this->get("security.authentication_utils");
        $lastError = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            "_username" => $lastUsername
        ]);

        return $this->render("@WebShop/security/login.html.twig", [
            "error" => $lastError,
            "login_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     * @Method("GET")
     */
    public function logoutAction()
    {
        if (!$this->get("security.authorization_checker")->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("homepage");
        }

        throw new \Exception("This page should not be reached.");
    }
}
