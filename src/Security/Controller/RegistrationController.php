<?php

namespace App\Security\Controller;

use App\Security\Form\RegistrationFormType;
use App\Security\Services\EmailVerifier;
use App\Security\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

/**
 * Class RegistrationController
 * @package App\Security\Controller
 */
class RegistrationController extends AbstractController
{
    /** @var EmailVerifier */
    /* private EmailVerifier $emailVerifier; */

    public function __construct(/* EmailVerifier $emailVerifier */)
    {
        /* $this->emailVerifier = $emailVerifier; */
    }

    /**
     * Register User
     * 
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher,
     * @param EntityManagerInterface $entityManager
     * @Route("/register", name="app_register")
     * @return Response
     * @return RedirectResponse
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        try {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                /* $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('surikatstudio@gmail.com', 'Surikat Mail'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('@security/registration/confirmation_email.html.twig')
                ); */
                // do anything else you need here, like send an email

                return $this->redirectToRoute('MemberPostsList');
            }

            return $this->render('@security/registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
    }

    /**
     * Register User
     * 
     * @param Request $request
     * @Route("/verify/email", name="app_verify_email")
     * @return RedirectResponse
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /* $this->emailVerifier->handleEmailConfirmation($request, $this->getUser()); */
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('danger', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('homepage');
    }
}
