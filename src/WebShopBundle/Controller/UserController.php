<?php

namespace WebShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\ProductsOrder;
use WebShopBundle\Entity\Role;
use WebShopBundle\Entity\User;
use WebShopBundle\Form\ProfileEditForm;
use WebShopBundle\Form\RegisterForm;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @Method("GET")
     * @return Response
     */
    public function registerAction()
    {
        if ($this->get("security.authorization_checker")->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("homepage");
        }

        $form = $this->createForm(RegisterForm::class);

        return $this->render("@WebShop/user/register.html.twig", [
            "register_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/register", name="user_register_process")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function registerProcessAction(Request $request)
    {
        if ($this->get("security.authorization_checker")->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("homepage");
        }

        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $userRole = $em->getRepository(Role::class)
            ->findOneBy(["name" => "ROLE_USER"]);

        $user->addRole($userRole);

        $form = $this->createForm(RegisterForm::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->get("security.authentication.guard_handler")
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get("web_shop.security.login_form_authenticator"),
                    "main"
                );
        }

        return $this->render("@WebShop/user/register.html.twig", [
            "register_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function profileAction()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        return $this->render("@WebShop/user/profile.html.twig", [
            "user" => $currentUser
        ]);
    }

    /**
     * @Route("/profile/edit", name="user_profile_edit")
     * @Security(expression="is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function editProfileAction(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $form = $this->createForm(ProfileEditForm::class, $currentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();

            $this->addFlash("success", "Profile updated!");

            return $this->redirectToRoute("user_profile");
        }

        return $this->render("@WebShop/user/edit.html.twig", [
            "edit_form" => $form->createView()
        ]);
    }
}