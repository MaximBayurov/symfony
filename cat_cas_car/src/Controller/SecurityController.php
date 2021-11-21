<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guard,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $manager,
        Mailer $mailer
    ) {
        $form = $this->createForm(UserRegistrationFormType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UserRegistrationFormModel $userModel
             */
            $userModel = $form->getData();
            
            $user = new User();
            
            $user
                ->setEmail($userModel->getEmail())
                ->setFirstName($userModel->getFirstName())
                ->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $userModel->getPlainPassword()
                ))
                ->setIsActive(true)
            ;
    
            $manager->persist($user);
            $manager->flush();
    
            $mailer->sendWelcomeMail($user);
            
            return $guard->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }
        
        return $this->render(
            'security/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]
        );
    }
}
