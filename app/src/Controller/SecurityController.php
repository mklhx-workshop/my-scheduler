<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function accountInfo()
    {
        // allow any authenticated user - we don't care if they just
        // logged in, or are logged in via a remember me cookie
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
    }

    public function resetPassword()
    {
        // require the user to log in during *this* session
        // if they were only logged in via a remember me cookie, they
        // will be redirected to the login page
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * @Route("/forgot-password", methods={"GET","POST"}, name="forgot_password")
     */
    public function askChangePassword(Request $request, UserRepository $userRepository, \Swift_Mailer $mailer): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->getForm();
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $userRepository->findOneBy(['email' => $form['email']->getData()]);
                $token = md5(uniqid($user->getEmail(), true));
                $session = $session = $this->get('session');
                $session->set('change_password_token', $token);
                $message = (new \Swift_Message('Forgot Password'))
                    ->setFrom('security@scheduler.io')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                        // templates/emails/registration.html.twig
                            'emails/forgotPassword.html.twig',
                            [
                                'userName' => $user->getUsername(),
                                'email' => $user->getemail(),
                                'token' => $token
                            ]
                        ),
                        'text/html'
                    )/*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'emails/registration.txt.twig',
                        ['name' => $name]
                    ),
                    'text/plain'
                )
                */
                ;

                $mailer->send($message);
                $this->addFlash('success', "You'll receive a confirmation email to change you password.");
                $this->redirectToRoute('login');
            }

        return $this->render('security/AskChangePassword.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/change-password", methods={"GET","POST"}, name="change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $token = $request->query->get('key');
        $email = $request->query->get('email');

        $session = $this->get('session');
        $checkerToken = $session->get('change_password_token');
        $user = $userRepository->findOneBy(['email' => $email]);

        $form = $this->createFormBuilder()
            ->add('plainPassword', PasswordType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($token != $checkerToken) {
                $this->addFlash('danger', "Error security token are not the same. You're not allowed to change this password.");
                return $this->redirectToRoute('forgot_password');
            }

            $password = $encoder->encodePassword($user, $form['plainPassword']->getData());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "Your password has changed. Please login.");
            $this->redirectToRoute("login");
        }

        return $this->render("security/ChangePassword.html.twig", ['form' => $form->createView()]);

    }
}
