<?php

namespace App\Installer\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Services\AppConfigurator;
use App\Security\Form\RegistrationFormType;
use App\Security\Services\EmailVerifier;
use App\Security\Entity\User;

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
     * Init App Controller Step One
     * 
     * @param string $init = null
     * @Route("/{init}",
     * name="init_app",
     * priority=1,
     * defaults={"init":null}
     * )
     * @return Response
     * @return RedirectResponse
     */
    public function initFirstStep(
        $init = null
    ): Response
    {
        try {
            if ($init != null && $init == 'basic-config') {
                $initialisation =  $this->appConfigurator->initApp(true, true, false);
                if ($initialisation) {
                    $this->addFlash(
                        'success',
                        'Installation success'
                    ); 
                    return $this->redirectToRoute('init_app_step_two');
                } else {
                    $this->addFlash(
                        'success',
                        'Installation error'
                    ); 
                }
            }
            if ($init != null && $init == 'custom-config') {
                $this->addFlash(
                    'warning',
                    'Installation Custom not implemented'
                ); 
            }
            
            return $this->render('@installer-admin/init-step-one.html.twig');
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
     * Init App Controller  Step Two
     * 
     * @param Request $request
     * @Route("/installer/init-step-two", name="init_app_step_two")
     * @return Response
     * @return RedirectResponse
     */
    public function initSecondStep(Request $request): Response
    {
        try {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $password = $form->get('plainPassword')->getData();
                $user = $this->appConfigurator->initAdminUser($user, $password);
                if (!$user) {
                    $this->addFlash(
                        'danger',
                        'App Init not working'
                    );
                    return $this->redirectToRoute('app_login');
                }
                
                $initialisation =  $this->appConfigurator->initApp(false, false, true);
                if ($initialisation) {
                    $this->addFlash(
                        'success',
                        'Installation success'
                    ); 
                    return $this->redirectToRoute('app_login');
                } else {
                    $this->addFlash(
                        'success',
                        'Installation error'
                    ); 
                }
                
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

            return $this->render('@installer-admin/init-step-two.html.twig', [
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

}
