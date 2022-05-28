<?php
// src/Controller/Core/MailerController.php
namespace App\Core\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
     * @param MailerInterface $mailer
     * @param int $id
     * @Route("/send-email/{id}",name="send_email")
     * @return RedirectResponse
     */
    public function sendEmail(Request $request, MailerInterface $mailer, int $id): RedirectResponse
    {
        try {
            $mailData = [];
            $formModel = $this->formVerificator($request, $id);
            // Construct mailData
            foreach ($formModel->getFields() as $field) {
                $fieldName = $field->getName();
                $mailData[$fieldName] = $request->request->get($fieldName);
            }
            // Prepare Mail
            $mailData = $this->mailerService->constructMail($mailData);
            
            $email = (new TemplatedEmail())
            ->from($mailData['email'])
            ->to($mailData['receiver'])
            ->replyTo($mailData['email'])
            ->subject($mailData['receiver'])
            ->htmlTemplate($mailData['mailTemplate'])
            ->context($mailData);

            $mailer->send($email);
            $this->addFlash(
                'success',
                'info.message.sended'
            );
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);

        }  catch (\Exception $e) {
            $this->addFlash(
                'danger',
                $e->getMessage()
            );
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        } 
        
    }


    /**
     * Test if FormModel exists and return it, or redirect to FormModel list index with an error message
     * 
     * @param Request $request
     * @param int $formId
     * @return FormIModel $formModel
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
            return new RedirectResponse($referer);
        }
        return $formModel;
    }
}