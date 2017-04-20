<?php

namespace WebShopBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebShopBundle\Entity\User;
use WebShopBundle\Form\AddEditUserForm;

/**
 * Class UsersController
 * @package WebShopBundle\Controller\Admin
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UsersController extends Controller
{
    /**
     * @Route("/users", name="admin_list_users")
     * @Method("GET")
     * @param Request $request
     * @return Response
     */
    public function listUsersAction(Request $request)
    {
        $pager  = $this->get('knp_paginator');
        /** @var User[] $users */
        $users = $pager->paginate(
            $this->getDoctrine()->getRepository(User::class)
                ->findByQueryBuilder(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("@WebShop/admin/users/list.html.twig", [
            "users" => $users
        ]);
    }

    /**
     * @Route("/users/delete/{id}", name="admin_delete_user")
     * @Method("POST")
     * @param User $user
     * @return RedirectResponse
     */
    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash("success", "User with email {$user->getEmail()} deleted successfully!");

        return $this->redirectToRoute("admin_list_users");
    }

    /**
     * @Route("/users/edit/{id}", name="admin_edit_user")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function editUserAction(User $user, Request $request)
    {
        $form = $this->createForm(AddEditUserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "User {$user->getEmail()} updated successfully!");

            return $this->redirectToRoute("admin_list_users");
        }

        return $this->render("@WebShop/admin/users/edit.html.twig", [
            "edit_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/users/add", name="admin_add_user")
     * @param Request $request
     * @return Response
     */
    public function addUserAction(Request $request)
    {
        $form = $this->createForm(AddEditUserForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "User {$user->getEmail()} added successfully!");

            return $this->redirectToRoute("admin_list_users");
        }

        return $this->render("@WebShop/admin/users/add.html.twig", [
            "edit_form" => $form->createView()
        ]);
    }
}
