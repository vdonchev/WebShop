<?php

namespace WebShopBundle\Security;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use WebShopBundle\Entity\User;
use WebShopBundle\Form\LoginForm;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $formFactory;
    private $entityManager;
    private $router;
    private $passwordEncoder;
    private $container;

    /**
     * LoginFormAuthenticator constructor.
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        UserPasswordEncoder $passwordEncoder,
        ContainerInterface $container)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->container = $container;
    }

    public function getCredentials(Request $request)
    {
        $isLoginFormRequest = $request->isMethod("POST") && $request->getPathInfo() === "/login";
        if (!$isLoginFormRequest) {
            return null;
        }

        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data["_username"]
        );

        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials["_username"];

        return $this->entityManager->getRepository(User::class)
            ->findOneBy([
                "email" => $username
            ]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials["_password"];
        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            $this->container->get("session")->getFlashBag()->add("success", "Logged in successfully!");
            return true;
        }

        return false;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate("security_login");
    }

    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('homepage');
    }
}