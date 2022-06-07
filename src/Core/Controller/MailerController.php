<?php
// src/Controller/Core/MailerController.php
namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Repository\FormModelRepository;
use App\Core\Entity\FormModel;
use App\Core\Services\MailerService;

class MailerController extends AbstractController
{
    /** @var FormModelRepository */
    private $formRepo;

    /** @var MailerService */
    private $mailerService;

    public function __construct(
        FormModelRepository $formRepo,
        MailerService $mailerService
    )
    {
        $this->formRepo = $formRepo;
        $this->mailerService = $mailerService;
    }

    /**
     * Public Mailer sender
     * 
     * @param Request $request
     * @param int $formId
     * @Route("/send-email/{formId}",name="app_send_email_handler")
     * @return RedirectResponse
     */
    public function sendEmail(Request $request, int $formId): RedirectResponse
    {
        try {
            $mailData = [];
            $formModel = $this->formVerificator($request, $formId);
            if ($formModel instanceof FormModel) {
                // Construct mailData
                $mailData = $this->mailerService->constructMail($request, $formModel, $mailData);
                // Send EMmail
                $mailData = $this->mailerService->sendMail($formModel, $mailData);
                if ($mailData instanceof \Exception) {
                    $this->addFlash(
                        'danger',
                        $mailData->getMessage()
                    );
                    $this->addFlash(
                        'warning',
                        'mail.status.error'
                    );
                } else {
                    $this->addFlash(
                        'success',
                        'mail.status.sended'
                    );
                }
            }
            $referer = $request->headers->get('referer');
            if (null != $referer) {
                return new RedirectResponse($referer);
            }
            return $this->redirectToRoute('homepage');
        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            $this->addFlash(
                'warning',
                'mail.status.error'
            );
            $referer = $request->headers->get('referer');
            if (null != $referer) {
                return new RedirectResponse($referer);
            }
            return $this->redirectToRoute('homepage');
        } 
        
    }


    /**
     * Test if FormModel exists and return it, or redirect to FormModel list index with an error message
     * 
     * @param Request $request
     * @param int $formId
     * @return FormModel $formModel
     * @return RedirectResponse
     */
    public function formVerificator(Request $request, int $formId)
    {
        $formModel = $this->formRepo->find($formId);
        if (!$formModel) {
            $this->addFlash(
                'warning',
                'error.form_not_exist'
            );
            $referer = $request->headers->get('referer');
            if (null != $referer) {
                return new RedirectResponse($referer);
            }
            return $this->redirectToRoute('homepage');
        }
        return $formModel;
    }
}