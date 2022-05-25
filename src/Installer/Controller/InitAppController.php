<?php

namespace App\Installer\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Services\AppConfigurator;
use App\Security\Form\RegistrationFormType;
use App\Security\Services\EmailVerifier;
use App\Security\Entity\User;
use App\Security\Repository\UserRepository;

/**
 * Class InitAppController
 * @package App\Installer\Controller
 */
class InitAppController extends AbstractController
{

    /** @var EmailVerifier */
    private EmailVerifier $emailVerifier;

    /** @var AppConfigurator */
    private $appConfigurator;
    
    public function __construct(
        AppConfigurator $appConfigurator,
        EmailVerifier $emailVerifier
    )
    {
        $this->appConfigurator = $appConfigurator;
        $this->emailVerifier = $emailVerifier;
    }
    
    /**
     * Init App Controller
     * 
     * @param Request $request
     * @Route("/", priority=1, name="init_app")
     * @return Response
     * @return RedirectResponse
     */
    public function initFirstStep(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository
    ): Response
    {
        try {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword( $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                $user->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']);
                $user->setIsVerified(true);
                $user->setIsActive(true);
                $user->setIsRestricted(false);
                $userRepository->add($user);

                // generate a signed url and email it to the user
                /* $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('surikatstudio@gmail.com', 'Surikat Mail'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('@security/registration/confirmation_email.html.twig')
                ); */
                return $this->redirectToRoute('init_second_step');
            }

            return $this->render('@installer-admin/init-first-step.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('page_error_handler', [
                'error_code' => 501
            ]));
        }
    }

    /**
     * Init App Controller
     * 
     * @param Request $request
     * @Route("/init-step-two", name="init_second_step")
     * @return Response
     * @return RedirectResponse
     */
    public function initSecondStep(
        Request $request
    ): Response
    {
        try {
            return $this->render('@installer-admin/init-second-step.html.twig', [);
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            return $this->redirect($this->generateUrl('page_error_handler', [
                'error_code' => 501
            ]));
        }
    }
}
